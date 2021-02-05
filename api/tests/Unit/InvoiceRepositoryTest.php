<?php

namespace Tests\Unit;

use App\Repositories\InvoiceRepository;
use Mockery as m;

use Mockery\Adapter\Phpunit\MockeryTestCase;

use Carbon\Carbon;


class InvoiceRepositoryTest extends MockeryTestCase
{
    public function mockeryTestSetUp()
    {
        $this->payment = m::mock('App\Models\Payment');

        $this->invoiceRepository = new InvoiceRepository($this->payment);
    }


    public function test_dayClosure_WhenCalledWithDueDate20210215_Return20210205()
    {

        $dueDate = Carbon::create("2021-02-15");

        $response = $this->invoiceRepository->dayClosure($dueDate);

        $this->assertEquals(Carbon::create("2021-02-05"), $response);

    }

    public function test_invoicePeriod_WhenCalledWithDueDate20210215_Return2021010620210205(){
        $dueDate = Carbon::create("2021-02-15");

        $response = $this->invoiceRepository->invoicePeriod($dueDate);

        $this->assertEquals(["initialDate" => "2021-01-06", "finalDate" => "2021-02-05"], $response);
    }

    public function test_invoiceByDueDate_WhenCalledWithAny_ReturnPayment(){

        $this->payment->shouldReceive('with', 'whereBetween', 'orderBy', 'get')
                       ->withAnyArgs()
                       ->andReturnSelf();

        $dueDate = Carbon::create("2021-02-15");

        $response = $this->invoiceRepository->invoiceByDueDate($dueDate);

        $this->assertInstanceOf("App\Models\Payment", $response);

    }

    public function test_valueTotalByInvoice_WhenCalledWith20210215_ReturnArrayValues(){
        $this->payment->shouldReceive('with', 'whereBetween', 'orderBy', 'get', 'sum')
                      ->withAnyArgs()
                      ->andReturnSelf();

        $dueDate = Carbon::create("2021-02-15");

        $response = $this->invoiceRepository->valueTotalByInvoice($dueDate);

        $this->assertTrue(is_array($response));
        $this->assertNotEquals(["monthYearRef"=> "2021-01", "totalInvoice"=> 4795.22], $response);
    }
}
