<?php

use App\Http\Controllers\PharmaController;
use App\Http\Controllers\UsersController;
use App\Models\PharmaRegisters;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::view('/Search','search');
Route::post('/Result',[UsersController::class,'SearchMedicine']);
Route::get('/Pharmacy/Details/{id}/{medicinename}',[UsersController::class,'PharmacyDetails']);
Route::get('/get_suggestions', [UsersController::class, 'autocomplete']);

Route::view('/Login','Pharma.Login');
Route::get('/PharmaLogin',[PharmaController::class,'LoginCheck']);
Route::view('/Register','Pharma.Register');
Route::post('/Registering',[PharmaController::class,'Registered']);
Route::get('/logout',[PharmaController::class,'Logout']);
//Options Pharmacy
Route::get('/dashboard', [PharmaController::class, 'dashboard'])->name('pharma.dashboard');
Route::get('/pharmacy/inventory',[PharmaController::class,'InventoryDetails']);
Route::get('/autocomplete', [PharmaController::class, 'autocomplete']);
Route::get('/autocompletewhole',[PharmaController::class,'autocompletewhole']);
Route::post('/InventorySearch',[PharmaController::class,'SearchInventory']);
Route::view('/pharmacy/entry','Pharma.entry');
Route::post('/inventory/add-medicine',[PharmaController::class,'MedicineAdded']);
Route::post('/inventory/update',[PharmaController::class,'UpdateMedicineBulk']);
Route::view('/pharmacy/adjust','Pharma.adjust');
Route::view('/pharmacy/import','Pharma.csvimport');
Route::post('/pharmacy/import/csv',[PharmaController::class,'import']);
Route::get('/pharmacy/predictions',[PharmaController::class,'Prediction']);
Route::get('/pharmacy/history',[PharmaController::class,'UserHistory']);
Route::get('customer-details/{mobile}',[PharmaController::class,'CustomerHistory']);
Route::get('/customer-history', [PharmaController::class, 'showCustomerHistory']);
// Show the combined form + list page
Route::get('/medicine-request', [PharmaController::class, 'medicineRequestsPage']);

// Store new medicine request
Route::post('/medicine-request', [PharmaController::class, 'storeMedicineRequest']);
Route::post('/medicine-request/{id}/update-status', [PharmaController::class, 'updateMedicineRequestStatus'])->name('medicine-request.update-status');





Route::get('/edit-bill', [PharmaController::class, 'EditBillView']);
Route::post('/finalize-bill', [PharmaController::class, 'FinalizeBill']);




Route::get('/chatbot', [UsersController::class, 'show'])->name('chatbot.show');
Route::post('/chatbot', [UsersController::class, 'process'])->name('chatbot.process');
