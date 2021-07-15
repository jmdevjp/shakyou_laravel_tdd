<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDataSeeder']);
    }

    /**
     * @test
     */
    public function api_customersにGETメソッドでアクセスできる()
    {
        $response = $this->get('api/customers');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_customersにPOSTメソッドでアクセスできる()
    {
        $customer = [
            'name' => 'customer_name',
        ];
        $response = $this->postJson('api/customers', $customer);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_customers_customer_idにGETメソッドでアクセスできる()
    {
        $customer_id = Customer::all()[0]->id;
        $response = $this->get('api/customers/' . $customer_id);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_customers_customer_idにGETメソッドでアクセスするとJSONが返却される()
    {
        $customer_id = Customer::all()[0]->id;
        $response = $this->get('api/customers/' . $customer_id);
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     * @test
     */
    public function api_customers_customer_idにGETメソッドで取得できる顧客情報のJSON形式は要件通りである()
    {
        $customer_id = Customer::all()[0]->id;
        $response = $this->get('api/customers/' . $customer_id);
        $customers = $response->json();
        $customer = $customers[0];
        $this->assertSame(['id', 'name'], array_keys($customer));
    }

    /**
     * @test
     */
    public function api_customers_customer_idにPUTメソッドでアクセスできる()
    {
        $response = $this->put('api/customers/1');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_customers_customer_idにDELETEメソッドでアクセスできる()
    {
        $response = $this->delete('api/customers/1');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_reportsにGETメソッドでアクセスできる()
    {
        $response = $this->get('api/reports');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_reportsにGETメソッドでアクセスするとJSONが返却される()
    {
        $response = $this->get('api/reports');
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     * @test
     */
    public function api_reportsにGETメソッドで取得できる訪問記録のJSON形式は要件通りである()
    {
        $response = $this->get('api/reports');
        $reports = $response->json();
        $report = $reports[0];
        $this->assertSame(['id', 'visit_date', 'customer_id', 'detail'], array_keys($report));
    }

    /**
     * @test
     */
    public function api_reportsにGETメソッドでアクセスすると4件の訪問記録リストが返却される()
    {
        $response = $this->get('api/reports');
        $response->assertJsonCount(4);
    }

    /**
     * @test
     */
    public function api_reportsにPOSTメソッドでアクセスできる()
    {
        $customer_id = Customer::all()[0]->id;
        $params = [
            'visit_date' => '2021-01-01',
            'customer_id' => $customer_id,
            'detail' => '詳細',
        ];
        $response = $this->postJson('api/reports', $params);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_reportsに訪問記録をPOSTするとreportsテーブルにそのデータが追加される()
    {
        $customer_id = Customer::all()[0]->id;
        $params = [
            'visit_date' => '2021-07-14',
            'customer_id' => $customer_id,
            'detail' => '今後のコンソールの扱いについてのミーティング。次回は2週間後',
        ];
        $this->postJson('api/reports', $params);
        $this->assertDatabaseHas('reports', $params);
    }

    /**
     * @test
     */
    public function api_reportsに訪問日が含まれない場合422UnprocessableEntityが返却される()
    {
        $customer_id = Customer::all()[0]->id;
        $params = [
            /*'visit_date' => '2021-07-14',*/
            'customer_id' => $customer_id,
            'detail' => '詳細',
        ];
        $response = $this->postJson('api/reports', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function api_reportsに顧客番号が含まれない場合422UnprocessableEntityが返却される()
    {
        $customer_id = Customer::all()[0]->id;
        $params = [
            'visit_date' => '2021-07-14',
            /*'customer_id' => $customer_id,*/
            'detail' => '詳細',
        ];
        $response = $this->postJson('api/reports', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function api_reportsに詳細が含まれない場合422UnprocessableEntityが返却される()
    {
        $customer_id = Customer::all()[0]->id;
        $params = [
            'visit_date' => '2021-07-14',
            'customer_id' => $customer_id,
            /*'detail' => '詳細',*/
        ];
        $response = $this->postJson('api/reports', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function api_reportsに訪問日が空の場合422UnprocessableEntityが返却される()
    {
        $customer_id = Customer::all()[0]->id;
        $params = [
            'visit_date' => '',
            'customer_id' => $customer_id,
            'detail' => '詳細',
        ];
        $response = $this->postJson('api/reports', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function api_reportsに顧客番号が10桁を超える場合422UnprocessableEntityが返却される()
    {
        $params = [
            'visit_date' => '2021-07-14',
            'customer_id' => 10000000000,
            'detail' => '詳細',
        ];
        $response = $this->postJson('api/reports', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function api_reportsに詳細が空の場合422UnprocessableEntityが返却される()
    {
        $customer_id = Customer::all()[0]->id;
        $params = [
            'visit_date' => '2021-07-14',
            'customer_id' => $customer_id,
            'detail' => '',
        ];
        $response = $this->postJson('api/reports', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function api_reports_report_idにGETメソッドでアクセスできる()
    {
        $report_id = Report::all()[0]->id;
        $response = $this->get('api/reports/' . $report_id);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_reports_report_idにGETメソッドでアクセスするとJSONが返却される()
    {
        $report_id = Report::all()[0]->id;
        $response = $this->get('api/reports/' . $report_id);
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     * @test
     */
    public function api_reports_report_idにGETメソッドでアクセスすると1件のJSONが返却される()
    {
        $customer_id = Customer::all()[0]->id;
        $response = $this->get('api/customers/' . $customer_id);
        $response->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function api_reports_report_idにGETメソッドで取得できる訪問記録のJSON形式は要件通りである()
    {
        $report_id = Report::all()[0]->id;
        $response = $this->get('api/reports/' . $report_id);
        $reports = $response->json();
        $report = $reports[0];
        $this->assertSame(['id', 'visit_date', 'customer_id', 'detail'], array_keys($report));
    }

    /**
     * @test
     */
    public function api_reports_report_idにPUTメソッドでアクセスできる()
    {
        $response = $this->put('api/reports/1');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_reports_report_idにDELETEメソッドでアクセスできる()
    {
        $response = $this->delete('api/reports/1');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_customersにGETメソッドでアクセスするとJSONが返却される()
    {
        $response = $this->get('api/customers');
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     * @test
     */
    public function api_customersにGETメソッドで取得できる顧客情報のJSON形式は要件通りである()
    {
        $response = $this->get('api/customers');
        $customers = $response->json();
        $customer = $customers[0];
        $this->assertSame(['id', 'name'], array_keys($customer));
    }

    /**
     * @test
     */
    public function api_customersにGETメソッドでアクセスすると2件の顧客リストが返却される()
    {
        $response = $this->get('api/customers');
        $response->assertJsonCount(2);
    }

    /**
     * @test
     */
    public function api_customersに顧客名をPOSTするとcustomerテーブルにそのデータが追加される()
    {
        $params = [
            'name' => '顧客名',
        ];
        $this->postJson('api/customers', $params);
        $this->assertDatabaseHas('customers', $params);
    }

    /**
     * @test
     */
    public function api_customersにnameが含まれない場合422UnprocessableEntityが返却される()
    {
        $params = [];
        $response = $this->postJson('api/customers', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function api_customersにnameが空の場合422UnprocessableEntityが返却される()
    {
        $params = [
            'name' => '',
        ];
        $response = $this->postJson('api/customers', $params);
        $response->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function POST_api_customersのエラーレスポンスの確認()
    {
        $params = [
            'name' => '',
        ];
        $response = $this->postJson('api/customers', $params);
        $error_response = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => [
                    'name は必須項目です',
                ]
            ],
        ];
        $response->assertExactJson($error_response);
    }
}
