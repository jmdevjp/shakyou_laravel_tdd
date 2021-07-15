<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Report;

class CustomerService
{
    public function getCustomers()
    {
        return Customer::query()->select(['id', 'name'])->get();
    }

    public function getCustomer($customer_id)
    {
        return Customer::find($customer_id)->select(['id', 'name'])->get();
    }

    public function addCustomer($name)
    {
        $customer = new Customer();
        $customer->name = $name;
        $customer->save();
    }

    public function getReports()
    {
        return Report::query()
            ->select(['id', 'visit_date', 'customer_id', 'detail'])
            ->get();
    }

    public function addReport($visit_date, $customer_id, $detail)
    {
        $report = new Report();
        $report->visit_date = $visit_date;
        $report->customer_id = $customer_id;
        $report->detail = $detail;
        $report->save();
    }

    public function getReport($report_id)
    {
        return Report::query()
            ->select(['id', 'visit_date', 'customer_id', 'detail'])
            ->where(['id' => $report_id])
            ->get();
    }
}
