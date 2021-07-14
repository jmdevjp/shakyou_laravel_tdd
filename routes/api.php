<?php

use App\Http\Controllers\ApiController;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('customers', [ApiController::class, 'getCustomers']);
Route::post('customers', [ApiController::class, 'postCustomers']);
Route::get('customers/{customer_id}', [ApiController::class, 'getCustomer']);
Route::put('customers/{customer_id}', [ApiController::class, 'putCustomer']);
Route::delete('customers/{customer_id}', [ApiController::class, 'deleteCustomer']);
Route::get('reports', function () {
    return response()->json(\App\Models\Report::query()->select(['id', 'visit_date', 'customer_id', 'detail'])->get());
});
Route::post('reports', function (\Illuminate\Http\Request $request) {
    $report = new Report();
    $report->visit_date = $request->json('visit_date');
    $report->customer_id = $request->json('customer_id');
    $report->detail = $request->json('detail');
    $report->save();
});
Route::get('reports/{report_id}', [ApiController::class, 'getReport']);
Route::put('reports/{report_id}', [ApiController::class, 'putReport']);
Route::delete('reports/{report_id}', [ApiController::class, 'deleteReport']);
