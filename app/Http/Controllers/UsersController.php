<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatives;
use App\Models\PharmaRegisters;
use App\Models\Prices;
use App\Models\MedicineAvailability;
use App\Models\MedicineSearchHistory;
use Illuminate\Support\Facades\Http;

class UsersController extends Controller
{
    function SearchMedicine(Request $request)
    {
        $inputName = $request->MedicineName;

        // Step 1: Try to find price for searched medicine
        $searchedMedicine = Prices::where('medicinename', $inputName)->first();

        // Step 2: Try to get alternatives (may be null)
        $alternative = Alternatives::where('medicinename', $inputName)->first();

        // Prepare medicine names for price lookup (at least searched medicine)
        $medicineNames = collect();

        if ($alternative) {
            $medicineNames = collect([
                $alternative->medicinename,
                $alternative->substitute0,
                $alternative->substitute1,
                $alternative->substitute2,
                $alternative->substitute3,
                $alternative->substitute4
            ])->filter()->unique();
        } else {
            // If no alternatives, still add the searched medicine for price lookup (if any)
            if ($searchedMedicine) {
                $medicineNames = collect([$searchedMedicine->medicinename]);
            }
        }
        // Step 3: Get prices for these medicines (can be empty if none found)
        $prices = Prices::whereIn('medicinename', $medicineNames)->orderBy('price', 'asc')->limit(10)->get(['medicinename', 'price']); // only fetch needed columns

        // Step 4: Get user location from request
        $userLat = $request->user_latitude;
        $userLon = $request->user_longitude;

        // Step 5: Get pharmacies that have this medicine
        $availability = MedicineAvailability::where('medicine_name', $inputName)->where('quantity','>',0)->pluck('pharmacy_id');

        // Step 6: Get nearby pharmacies if location available, else all pharmacies with medicine
        if ($userLat && $userLon) {
            $radius = 10; // 10 km radius

            $maxLat = $userLat + rad2deg($radius / 6371);
            $minLat = $userLat - rad2deg($radius / 6371);
            $maxLon = $userLon + rad2deg($radius / 6371 / cos(deg2rad($userLat)));
            $minLon = $userLon - rad2deg($radius / 6371 / cos(deg2rad($userLat)));

            $details = PharmaRegisters::select('*')
                ->selectRaw("6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            ) AS distance", [$userLat, $userLon, $userLat])
                ->whereIn('id', $availability)
                ->whereBetween('latitude', [$minLat, $maxLat])
                ->whereBetween('longitude', [$minLon, $maxLon])
                ->havingRaw('distance <= ?', [$radius])
                ->orderBy('distance')
                ->paginate(5);  // <-- Use paginate(5) here for pagination
        } else {
            // If no location, get all pharmacies that have medicine (paginate 5)
            $details = PharmaRegisters::whereIn('id', $availability)
                ->paginate(5);  // <-- paginate here too
        }

        // Step 7: Return view with all data (some may be null/empty)
        // Pass the pagination links to the view automatically with $details
        return view('/Search', [
            'searchedMedicine' => $searchedMedicine,
            'prices' => $prices,
            'details' => $details,
            'alternative' => $alternative // pass alternatives to view if you want
        ]);
    }



    function PharmacyDetails($id, $medicinename)
    {
        $PharmaDetails = PharmaRegisters::where('id', $id)->first();
        return view('Pharma.pharmadetails', ['PharmaDetails' => $PharmaDetails, 'medicinename' => $medicinename]);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        $results = Alternatives::select('medicinename')->where('medicinename', 'LIKE', '%' . $query . '%')->distinct()->limit(5)->get();

        return response()->json($results);
    }


    public function show()
    {
        return view('chatbot');
    }

    public function process(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);



        $userMessage = $request->input('message');
        $model = "gemini-2.0-flash";
        $botReply = '';

        $greetings = ['hi', 'hello', 'hey', 'hey hi', 'good morning', 'good afternoon', 'good evening'];

        $lowerMsg = strtolower(trim($userMessage));

        // If the message is a simple greeting, respond directly and skip medicine logic
        if (in_array($lowerMsg, $greetings)) {
            return response()->json(['response' => "Hello! How can I assist you today? Please remember that I cannot provide diagnoses or replace a doctor's advice. For any specific medical conditions, please consult a healthcare professional."]);
        }


        // --- Logic to detect medicine queries and extract medicine name ---
        $medicineName = null;
        $isMedicineQuery = false;

        // Pattern 1: "Which store has [Medicine Name]?" or similar queries
        if (preg_match('/(?:which store has|where can i buy|do you have|where is|stock of|availability of|get|need)\s+([\w\s\d-]+(?: \d+[mgl]?g?)?\s*(?:tablet|capsule|syrup|injection|cream|gel|solution|drops)?)/i', $userMessage, $matches)) {
            $medicineName = trim($matches[1]);
            $isMedicineQuery = true;
        }
        // Pattern 2: "[Medicine Name] available?" or "[Medicine Name] stock?"
        else if (preg_match('/([\w\s\d-]+(?: \d+[mgl]?g?)?)\s*(?:medicine|drug|pharmacy|store|available|stock|get|need|tablet|capsule|syrup|injection|cream|gel|solution|drops)\??$/i', $userMessage, $matches)) {
            $medicineName = trim($matches[1]);
            $isMedicineQuery = true;
        }
        // Pattern 3: Simple medicine name (less strict)
        else {
            if (preg_match('/([\w\s\d-]+(?: \d+[mgl]?g?)?\s*(?:tablet|capsule|syrup|injection|cream|gel|solution|drops)?)/i', $userMessage, $matches)) {
                $candidateMedicineName = trim($matches[1]);
                if (str_word_count($candidateMedicineName) < 5 && !empty($candidateMedicineName)) {
                    $medicineName = $candidateMedicineName;
                    $isMedicineQuery = true;
                }
            }
        }

        // --- Attempt medicine availability lookup if query detected ---
        if ($isMedicineQuery && !empty($medicineName)) {
            $originalUserInputMedicineName = $medicineName;

            // Clean name for search (remove dosage forms)
            $searchMedicineName = preg_replace('/\s*(tablet|capsule|syrup|injection|cream|gel|solution|drops)\s*$/i', '', $medicineName);
            $searchMedicineName = trim($searchMedicineName);

            $foundFullMedicineName = null;
            $availability = collect();

            // Step 1: Search MedicineAvailability using LIKE
            $possibleAvailabilities = MedicineAvailability::where('medicine_name', 'LIKE', '%' . $searchMedicineName . '%')->get();

            if ($possibleAvailabilities->isNotEmpty()) {
                $bestMatch = $possibleAvailabilities->first();
                foreach ($possibleAvailabilities as $avail) {
                    if (strtolower($avail->medicine_name) === strtolower($searchMedicineName)) {
                        $bestMatch = $avail;
                        break;
                    }
                }
                $foundFullMedicineName = $bestMatch->medicine_name;
                // Use LIKE here for availability check to catch partial matches
                $availability = MedicineAvailability::where('medicine_name', 'LIKE', '%' . $foundFullMedicineName . '%')->pluck('pharmacy_id');
            }

            // Step 2: If not found, check Alternatives table (if exists)
            if ($availability->isEmpty() && class_exists(Alternatives::class)) {
                $alternativeEntry = Alternatives::where(function ($query) use ($searchMedicineName) {
                    $query->where('medicinename', 'LIKE', '%' . $searchMedicineName . '%')
                        ->orWhere('substitute0', 'LIKE', '%' . $searchMedicineName . '%')
                        ->orWhere('substitute1', 'LIKE', '%' . $searchMedicineName . '%')
                        ->orWhere('substitute2', 'LIKE', '%' . $searchMedicineName . '%')
                        ->orWhere('substitute3', 'LIKE', '%' . $searchMedicineName . '%')
                        ->orWhere('substitute4', 'LIKE', '%' . $searchMedicineName . '%');
                })->first();

                if ($alternativeEntry) {
                    $possibleMedicineNamesFromAlternatives = collect([
                        $alternativeEntry->medicinename,
                        $alternativeEntry->substitute0,
                        $alternativeEntry->substitute1,
                        $alternativeEntry->substitute2,
                        $alternativeEntry->substitute3,
                        $alternativeEntry->substitute4
                    ])->filter()->unique()->toArray();

                    $availability = MedicineAvailability::whereIn('medicine_name', $possibleMedicineNamesFromAlternatives)->pluck('pharmacy_id');

                    if ($availability->isNotEmpty()) {
                        $foundMedicineRecord = MedicineAvailability::whereIn('pharmacy_id', $availability)->first();
                        if ($foundMedicineRecord) {
                            $foundFullMedicineName = $foundMedicineRecord->medicine_name;
                        }
                    }
                }
            }

            if ($availability->isEmpty()) {
                $botReply = "I couldn't find <strong>{$originalUserInputMedicineName}</strong> (or its variants) in stock at any registered pharmacies. It might be out of stock, not listed yet, or you might have used a different name.<br><br>";
                $useGeminiForSubstance = true;
            } else {
                $pharmacies = PharmaRegisters::whereIn('id', $availability)->get();

                if ($pharmacies->isEmpty()) {
                    $botReply = "I found <strong>" . ($foundFullMedicineName ?? $originalUserInputMedicineName) . "</strong> listed, but no pharmacy details are available right now for its locations. Please try again later or check directly with local pharmacies.";
                } else{
                    $botReply = "You can find <strong>" . htmlspecialchars($foundFullMedicineName ?? $originalUserInputMedicineName) . "</strong> at the following pharmacies:\n\n";
                    $botReply .= "<ul>";
                    foreach ($pharmacies as $pharmacy) {
                        $url = url("/Pharmacy/Details/{$pharmacy->id}/" . urlencode($foundFullMedicineName ?? $originalUserInputMedicineName));
                        $botReply .= "<li>";
                        // Pharmacy name plain text
                        $botReply .= htmlspecialchars($pharmacy->pharmacy_name);
                        // Address if available
                        if ($pharmacy->address) {
                            $botReply .= " - " . htmlspecialchars($pharmacy->address);
                        }
                        // Phone if available
                        if ($pharmacy->phone) {
                            $botReply .= " (Phone: " . htmlspecialchars($pharmacy->phone) . ")";
                        }
                        // Add navigate icon link at end (using a simple Unicode link emoji)
                        // $botReply .= " <a href=\"{$url}\" target=\"_blank\" title=\"View pharmacy details\" style=\"text-decoration:none; margin-left:8px; color:#06b6d4; font-weight:bold; font-size:1.1em; vertical-align:middle;\">🔗</a>"; // Changed to link emoji
                        $botReply .= "</li>";
                    }
                    $botReply .= "</ul>";
                    $botReply .= "<br><strong>Please note:</strong> Availability may vary, it's best to call ahead. You can use the search page to find nearby pharmacies based on your location.";
                }
            }
        }

        // --- Use Gemini for fallback or general queries ---
        if (empty($botReply) || (isset($useGeminiForSubstance) && $useGeminiForSubstance)) {
            $systemPrompt = "You are a helpful and knowledgeable medical assistant named MediGuide. Provide short, clear, and safe medical advice without lengthy explanations. Highlight important terms or warnings by surrounding them with double asterisks like **this**. Do not provide information that requires a medical diagnosis or replaces a doctor's advice. Always advise consulting a healthcare professional for personalized medical conditions. If asked about medicine availability, state you can check registered pharmacies, but for current stock, suggest calling the pharmacy directly. If a medicine is not found in your database, state so. For general health queries, provide concise and accurate information.";

            $geminiQuery = (isset($useGeminiForSubstance) && $isMedicineQuery && !empty($originalUserInputMedicineName))
                ? "What is " . $originalUserInputMedicineName . " used for? Provide general info."
                : $userMessage;

            $fullPrompt = $systemPrompt . "\n\nUser: " . $geminiQuery;

            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . env('GEMINI_API_KEY'), [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $fullPrompt]
                        ],
                    ],
                ],
            ]);

            $geminiResponse = $response->json('candidates.0.content.parts.0.text') ?? 'Sorry, MediGuide could not generate a response for your request. Please try rephrasing your question.';

            if (!empty($botReply) && (isset($useGeminiForSubstance) && $useGeminiForSubstance)) {
                $botReply .= "<br><br>Here is some general information about <strong>" . $originalUserInputMedicineName . "</strong>:<br>" . $geminiResponse;
            } else {
                $botReply = $geminiResponse;
            }
        }

        // Replace **bold** with <strong>
        $botReply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $botReply);

        return response()->json(['response' => $botReply]);
    }
}
