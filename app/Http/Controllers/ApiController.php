<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCustomers(CustomerService $customerService): \Illuminate\Http\JsonResponse
    {
        return response()->json($customerService->getCustomers());
    }

    public function postCustomers(Request $request, CustomerService $customerService)
    {
        $this->validate($request, ['name' => 'required']);
        $customerService->addCustomer($request->json('name'));
    }

    public function getCustomer()
    {
    }

    public function putCustomer()
    {
    }

    public function deleteCustomer()
    {
    }

    public function getReports(): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            \App\Models\Report::query()
            ->select(['id', 'visit_date', 'customer_id', 'detail'])
            ->get()
        );
    }

    public function postReport(Request $request)
    {
        if (!$request->json('visit_date') || !$request->json('customer_id') || !$request->json('detail')) {
            return response()
                ->make('', \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $report = new Report();
        $report->visit_date = $request->json('visit_date');
        $report->customer_id = $request->json('customer_id');
        $report->detail = $request->json('detail');
        $report->save();
    }

    public function getReport()
    {
    }

    public function putReport()
    {
    }

    public function deleteReport()
    {
    }
}
