<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCustomers(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Customer::query()->select(['id', 'name'])->get());
    }

    public function postCustomers(Request $request)
    {
        $this->validate(
            $request,
            ['name' => 'required'],
            ['name.required' => ':attribute は必須項目です']
        );
        $customer = new \App\Models\Customer();
        $customer->name = $request->json('name');
        $customer->save();
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

    public function getReports()
    {
    }

    public function postReport()
    {
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
