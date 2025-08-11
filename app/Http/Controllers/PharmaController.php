<?php

namespace App\Http\Controllers;

use App\Models\Alternatives;
use Illuminate\Http\Request;
use App\Models\PharmaRegisters;
use App\Models\MedicineAvailability;
use App\Models\Prices;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PharmacySale;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MedicineRequest;

class PharmaController extends Controller
{
    function Registered(Request $request)
    {
        $status = PharmaRegisters::create([
            'pharmacy_name' => $request->pharmacy_name,
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'address' => $request->address,
            'map_link' => $request->map_link,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        if ($status) {
            return "Successfully Registered";
        }
    }

    function LoginCheck(Request $request)
    {
        $status = PharmaRegisters::where('email', $request->email)
            ->where('password', $request->password)
            ->first();
        if ($status) {
            $request->session()->put('Pharmacy', $status->pharmacy_name);
            $request->session()->put('Pharmacy_id', $status->id);
            return redirect()->route('pharma.dashboard');


        }
    }

    function Logout()
    {
        session()->flush();
        return redirect('/Login');
    }


public function dashboard(Request $request)
{
    $pharmacyId = $request->session()->get('Pharmacy_id');

    $today = Carbon::today();
    $monthStart = Carbon::now()->startOfMonth();

    $todaySales = PharmacySale::where('pharmacy_id', $pharmacyId)
        ->whereDate('sold_at', $today)
        ->sum(DB::raw('quantity_sold * price_at_sale'));

    $monthSales = PharmacySale::where('pharmacy_id', $pharmacyId)
        ->whereBetween('sold_at', [$monthStart, Carbon::now()])
        ->sum(DB::raw('quantity_sold * price_at_sale'));

    return view('Pharma.dashboard', compact('todaySales', 'monthSales'));
}


    public function inventoryDetails(Request $request)
    {
        $pharmacyId = session('Pharmacy_id');
        $query = MedicineAvailability::where('pharmacy_id', $pharmacyId);
        if ($request->filled('search')) {
            $query->where('medicine_name', 'like', '%' . $request->search . '%');
        }
        $inventory = $query->paginate(10);
        return view('Pharma.inventory', compact('inventory'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        $results = MedicineAvailability::select('medicine_name')
            ->where('medicine_name', 'LIKE', '%' . $query . '%')
            ->distinct()
            ->limit(100)
            ->get();

        return response()->json($results);
    }

    public function autocompletewhole(Request $request)
    {
        $query = $request->get('query');

        $results = Prices::select('medicinename')
            ->where('medicinename', 'LIKE', '%' . $query . '%')
            ->distinct()
            ->limit(100)
            ->get();

        return response()->json($results);
    }

    public function SearchInventory(Request $request)
    {
        $medicine = $request->search;
        $pharmacyId = session('Pharmacy_id');
        $result = MedicineAvailability::where('pharmacy_id', $pharmacyId)
            ->where('medicine_name', $medicine)
            ->first();
        return view('Pharma.inventorysearch', ['inventory' => $result]);
    }

    public function MedicineAdded(Request $request)
    {
        $existing = MedicineAvailability::where('pharmacy_id', $request->pharmacy_id)
            ->where('medicine_name', $request->medicine_name)
            ->first();

        if ($existing) {
            // Add to existing stock, update price
            $newQuantity = $existing->quantity + $request->quantity;

            MedicineAvailability::where('pharmacy_id', $existing->pharmacy_id)
                ->where('medicine_name', $request->medicine_name)
                ->update([
                    'quantity' => $newQuantity,
                    'price' => $request->price,
                ]);

            $message = "✅ Medicine updated successfully:<br>"
                . "<strong>Medicine:</strong> {$request->medicine_name}<br>"
                . "<strong>New Quantity:</strong> {$newQuantity}<br>"
                . "<strong>Price:</strong> ₹{$request->price}";
        } else {
            // Insert new medicine
            MedicineAvailability::insert([
                'pharmacy_id' => $request->pharmacy_id,
                'medicine_name' => $request->medicine_name,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);

            $message = "➕ New medicine added:<br>"
                . "<strong>Medicine:</strong> {$request->medicine_name}<br>"
                . "<strong>Quantity:</strong> {$request->quantity}<br>"
                . "<strong>Price:</strong> ₹{$request->price}";
        }

        return redirect()->back()->with('success', $message);
    }

    public function UpdateMedicineBulk(Request $request)
    {
        $request->validate([
            'pharmacy_id' => 'required|integer',
            'medicine_name.*' => 'required|string',
            'quantity.*' => 'required|integer|min:1',
        ]);

        $pharmacyId = $request->input('pharmacy_id');
        $medicines = $request->input('medicine_name');
        $quantities = $request->input('quantity');

        $summary = [];
        $errors = [];

        // Load pharmacy info once
        $pharmacy = PharmaRegisters::find($pharmacyId);

        foreach ($medicines as $index => $medName) {
            $qty = $quantities[$index];

            $medicine = MedicineAvailability::where('pharmacy_id', $pharmacyId)
                ->where('medicine_name', $medName)
                ->first();

            if (!$medicine) {
                $errors[] = "❌ <strong>{$medName}</strong> not found in stock.";
                continue;
            }

            if ($medicine->quantity < $qty) {
                $errors[] = "⚠️ Not enough stock for <strong>{$medName}</strong>. Available: {$medicine->quantity}";
                continue;
            }

            // Do NOT update quantity or save here. Just prepare summary.

            $amount = $qty * $medicine->price;

            $summary[] = [
                'medicine' => $medicine->medicine_name,
                'quantity' => $qty,
                'price' => $medicine->price,
                'amount' => $amount,
            ];
        }

        if (count($summary) > 0) {
            session([
                'editable_bill' => [
                    'summary' => $summary,
                    'pharmacy_id' => $pharmacyId,
                    'pharmacy_name' => $pharmacy->pharmacy_name ?? 'Pharmacy',
                    'pharmacy_address' => $pharmacy->address ?? '',
                    'pharmacy_phone' => $pharmacy->phone ?? '',
                    'date' => now()->format('d M Y, h:i A')
                ]
            ]);

            return redirect('/edit-bill');
        }

        return redirect()->back()->withErrors($errors);
    }

    public function EditBillView()
    {
        $data = session('editable_bill');
        if (!$data) return redirect()->back()->with('error', 'No bill found.');

        return view('pharma.edit_bill', $data);
    }

    public function finalizeBill(Request $request)
    {
        $pharmacyId = $request->input('pharmacy_id');
        $customerMobile = $request->input('customer_mobile');
        $customerEmail = $request->input('customer_email');
        $overallDiscountPercent = floatval($request->input('overall_discount', 0));
        $items = $request->input('items', []);

        $subtotal = 0;
        $processedItems = [];

        foreach ($items as $item) {
            $medicine = $item['medicine'];
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);  // original unit price

            $originalAmount = $quantity * $price;
            $discountAmount = $originalAmount * ($overallDiscountPercent / 100);
            $finalAmount = $originalAmount - $discountAmount;

            $subtotal += $originalAmount;

            $processedItems[] = [
                'medicine' => $medicine,
                'quantity' => $quantity,
                'price' => $price,
                'amount' => $originalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ];
        }

        $totalDiscount = $subtotal * ($overallDiscountPercent / 100);
        $grandTotal = $subtotal - $totalDiscount;

        // Fetch pharmacy info
        $pharmacy = PharmaRegisters::find($pharmacyId);

        // Deduct stock and save sales after user confirms customer info
        $this->updateStockAndSalesFromBill($pharmacyId, $processedItems, $customerMobile);

        // Generate PDF content
        $pdf = PDF::loadView('Pharma.pdf_bill', [
            'pharmacy' => $pharmacy,
            'customer_mobile' => $customerMobile,
            'customer_email' => $customerEmail,
            'summary' => $processedItems,
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal,
            'overall_discount' => $overallDiscountPercent,
            'date' => now(),
        ]);

        // Define filename and path
        $filename = 'final-bill-' . time() . '.pdf';
        $storagePath = storage_path('app/public/bills');

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $pdfFilePath = $storagePath . '/' . $filename;

        // Save PDF to storage
        file_put_contents($pdfFilePath, $pdf->output());

        // Prepare public URL for PDF (make sure you ran: php artisan storage:link)
        $pdfPublicUrl = asset('storage/bills/' . $filename);

        // Pass data to the view that shows embedded PDF + summary + download link
        return view('Pharma.show_bill_pdf', [
            'pdf_path' => $pdfPublicUrl,
            'subtotal' => $subtotal,
            'overall_discount' => $overallDiscountPercent,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal,
            'date' => now()->format('d M Y, h:i A'),
        ]);
    }

    /**
     * Update stock and save sales records from finalized bill items.
     *
     * @param int $pharmacyId
     * @param array $items
     * @param string|null $customerMobile
     * @return void
     */
    private function updateStockAndSalesFromBill($pharmacyId, $items, $customerMobile)
    {
        foreach ($items as $item) {
            $medicine = MedicineAvailability::where('pharmacy_id', $pharmacyId)
                ->where('medicine_name', $item['medicine'])
                ->first();

            if (!$medicine) {
                // Medicine not found, skip or handle
                continue;
            }

            $qty = $item['quantity'];

            if ($medicine->quantity < $qty) {
                // Not enough stock - skip or handle error if needed
                continue;
            }

            $stockBeforeSale = $medicine->quantity;

            // Deduct stock
            $medicine->quantity -= $qty;
            $medicine->save();

            // Save sale
            PharmacySale::create([
                'pharmacy_id' => $pharmacyId,
                'medicine_name' => $medicine->medicine_name,
                'quantity_sold' => $qty,
                'sold_at' => now(),
                'price_at_sale' => $medicine->price,
                'stock_before_sale' => $stockBeforeSale,
                'day_of_week' => now()->format('l'),
                'season' => $this->getCurrentSeason(),
                'weather_condition' => $this->getCurrentWeatherCondition(),
                'pharmacy_area' => $this->getPharmacyArea($pharmacyId),
                'customer_mobile' => $customerMobile,
            ]);
        }
    }

    /**
     * Example helper to get current season — implement your logic here.
     */
    private function getCurrentSeason()
    {
        $month = now()->month;
        if (in_array($month, [12, 1, 2])) return 'Winter';
        if (in_array($month, [3, 4, 5])) return 'Spring';
        if (in_array($month, [6, 7, 8])) return 'Summer';
        if (in_array($month, [9, 10, 11])) return 'Autumn';
        return 'Unknown';
    }

    /**
     * Example helper stub for weather condition — you need to implement actual weather fetching logic or leave static.
     */
    private function getCurrentWeatherCondition()
    {
        // For demo, return static or integrate with weather API.
        return 'Clear';
    }

    /**
     * Example helper to get pharmacy area from pharmacy id.
     */
    private function getPharmacyArea($pharmacyId)
    {
        $pharmacy = PharmaRegisters::find($pharmacyId);
        return $pharmacy ? ($pharmacy->area ?? 'Unknown') : 'Unknown';
    }

    public function import(Request $request)
    {
        $pharmacyId = session('Pharmacy_id');
        if (!$pharmacyId) {
            return back()->withErrors(['file' => 'Pharmacy ID not found in session. Please login again.']);
        }

        // Read file as array with heading row (first sheet only)
        $rows = Excel::toArray(null, $request->file('file'))[0]; // get first sheet

        if (count($rows) < 1) {
            return back()->withErrors(['file' => 'Uploaded file is empty']);
        }

        // Extract header row (first row)
        $header = array_map('strtolower', $rows[0]);

        // Check required columns
        $requiredColumns = ['medicine_name', 'quantity', 'price'];
        if (array_diff($requiredColumns, $header)) {
            return back()->withErrors(['file' => 'File must contain medicine_name, quantity, and price columns']);
        }

        // Map header columns to their indexes for row access
        $colIndexes = array_flip($header);

        // Loop through all rows except header
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            // Defensive: skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $medicineName = $row[$colIndexes['medicine_name']] ?? null;
            $quantity = $row[$colIndexes['quantity']] ?? null;
            $price = $row[$colIndexes['price']] ?? null;

            if (!$medicineName || !is_numeric($quantity) || !is_numeric($price)) {
                // skip invalid row or handle validation here
                continue;
            }

            DB::table('pharmacy_medicine_available')->updateOrInsert(
                [
                    'pharmacy_id' => $pharmacyId,
                    'medicine_name' => $medicineName,
                ],
                [
                    'quantity' => (int) $quantity,
                    'price' => (float) $price,
                ]
            );
        }
        session()->flash('success', 'Inventory imported successfully.');
        return back()->with('success', 'Inventory imported successfully.');
    }

    public function Prediction(Request $request)
    {
        $pharmacyId = session('Pharmacy_id');
        $pharmacy = PharmaRegisters::find($pharmacyId);

        if (!$pharmacy) {
            return redirect('/Login');
        }

        // --- LOW STOCK: Qty < 20 ---
        $lowStock = MedicineAvailability::where('pharmacy_id', $pharmacyId)
            ->where('quantity', '<', 20)
            ->get();

        // --- NO SALES IN LAST 30 DAYS ---
        $thresholdDate = Carbon::now()->subDays(30);

        $recentlySold = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->where('sold_at', '>=', $thresholdDate)
            ->pluck('medicine_name')
            ->unique();

        $inventory = MedicineAvailability::where('pharmacy_id', $pharmacyId)->get();

        $noSalesStock = $inventory->filter(function ($medicine) use ($recentlySold) {
            return !$recentlySold->contains($medicine->medicine_name);
        })->map(function ($medicine) {
            return [
                'medicine_name' => $medicine->medicine_name,
                'current_stock' => $medicine->quantity,
                'sales_last_30_days' => 0,
            ];
        })->values(); // Ensure it's a collection

        // --- HOT STOCK PREDICTIONS ---
        $hotStock = collect();

        foreach ($inventory as $medicine) {
            $latestSale = PharmacySale::where('pharmacy_id', $pharmacyId)
                ->where('medicine_name', $medicine->medicine_name)
                ->orderByDesc('sold_at')
                ->first();

            $price = $latestSale?->price ?? 10;
            $stock = $medicine->quantity ?? 100;

            $payload = [
                'pharmacy_id'       => (string) $pharmacyId,
                'medicine_name'     => $medicine->medicine_name,
                'date'              => Carbon::today()->toDateString(),
                'price_at_sale'     => $price,
                'stock_before_sale' => $stock,
                'weather_condition' => 'Clear',
                'pharmacy_area'     => $pharmacy->area ?? 'Urban',
            ];

            try {
                $response = Http::timeout(5)->post('http://127.0.0.1:5000/predict_quantity', $payload);
                $data = $response->json();

                $predictedQty = $data['predicted_quantity'] ?? 0;

                if ($predictedQty > $stock) {
                    $hotStock->push([
                        'medicine_name' => $medicine->medicine_name,
                        'current_stock' => $stock,
                        'predicted_sales_today' => $predictedQty,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("[Prediction Error] " . $e->getMessage());
            }
        }

        return view('Pharma.Predictions', [
            'lowStock'     => $lowStock,
            'hotStock'     => $hotStock,
            'noSalesStock' => $noSalesStock,
        ]);
    }

    public function UserHistory(Request $req)
    {
        $pharmacyId = session('Pharmacy_id');
        $customer = PharmacySale::where('pharmacy_id', $pharmacyId)
            ->whereNotNull('customer_mobile')
            ->where('customer_mobile', '!=', '')
            ->select('customer_mobile')
            ->distinct()
            ->get();
        return view('Pharma.customerhistory', ['customers' => $customer]);
    }


    public function showCustomerHistory(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $customers=PharmacySale::where('customer_mobile', 'like', '%' . $search . '%');
        }

        // ✅ Get unique customer_mobile rows (grouped)
        $customers = $customers
            ->select('customer_mobile')
            ->groupBy('customer_mobile')
            ->get();

        return view('Pharma.customerhistory', [
            'customers' => $customers
        ]);
    }


    public function CustomerHistory(Request $req, $mobile)
    {
        $pharmacyId = session('Pharmacy_id');
        $customer = PharmacySale::where('pharmacy_id', $pharmacyId)->where('customer_mobile', $mobile)->get();
        return view('Pharma.sales', ['MainContent' => $customer]);
    }


    public function medicineRequestsPage()
    {
        // Fetch all medicine requests to show in the "Previous Requests" tab
        $requests = MedicineRequest::latest()->get();

        // Pass the requests to the single combined view
        return view('Pharma.medicine_request_form', compact('requests'));
    }

    public function storeMedicineRequest(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|string|max:15',
            'medicine_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            // 'status' is optional; you can skip validation or include if you want
        ]);

        MedicineRequest::create($validated);

        return redirect()->back()->with('success', 'Medicine request saved successfully!');
    }

    public function updateMedicineRequestStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,fulfilled,cancelled',
        ]);

        $medicineRequest = MedicineRequest::findOrFail($id);
        $medicineRequest->status = $validated['status'];
        $medicineRequest->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function listMedicineRequests(Request $request)
{
    $query = MedicineRequest::query();

    if ($request->filled('mobile')) {
        $mobile = $request->input('mobile');
        // Filter rows with mobile containing the input (partial match)
        $query->where('customer_mobile', 'like', "%{$mobile}%");
    }

    $requests = $query->latest()->get();

    return view('Pharma.medicine_request_list', compact('requests'));
}

}
