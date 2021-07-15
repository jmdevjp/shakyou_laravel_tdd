<?php

namespace App\Http\Controllers;

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

    public function getCustomer(CustomerService $customerService, $customer_id): \Illuminate\Http\JsonResponse
    {
        return response()->json($customerService->getCustomer($customer_id));
    }

    public function putCustomer()
    {
    }

    public function deleteCustomer()
    {
    }

    public function getReports(CustomerService $customerService): \Illuminate\Http\JsonResponse
    {
        return response()->json($customerService->getReports());
    }

    public function postReport(Request $request, CustomerService $customerService)
    {
        $this->validate(
            $request,
            [
                'visit_date' => 'required',
                'customer_id' => 'required|digits_between:1,10',
                'detail' => 'required',
            ]
        );

        $visit_date = $request->json('visit_date');
        $customer_id = $request->json('customer_id');
        $detail = $request->json('detail');
        $customerService->addReport($visit_date, $customer_id, $detail);
    }

    public function getReport(CustomerService $customerService, $report_id): \Illuminate\Http\JsonResponse
    {
        return response()->json($customerService->getReport($report_id));
    }

    public function putReport()
    {
    }

    public function deleteReport()
    {
    }
}
