<?php

namespace Tests\Unit;

use App\Repositories\InvoiceRepository;
use Mockery as m;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class InvoiceRepositoryTest extends MockeryTestCase
{
    public function mockeryTestSetUp()
    {
        $this->payment = m::mock('App\Models\Payment');
        $this->carbon = m::mock('Carbon\Carbon');

        $this->invoiceRepository = new InvoiceRepository($this->payment);
    }


    public function test_invoicePeriod_WhenCalledWithDueDate20210215_Return2021010620210205(){
        $this->carbon->shouldReceive('create', 'day', 'copy', 'subDays', 'subMonth', 'addDay', 'format')
                     ->withAnyArgs()
                     ->andReturnSelf();

        $response = $this->invoiceRepository->invoicePeriod($this->carbon);

        $this->assertTrue(is_array($response));
    }

    public function test_invoiceByDueDate_WhenCalledWithAny_ReturnPayment(){

        $this->payment->shouldReceive('with', 'whereBetween', 'orderBy', 'get')
                       ->withAnyArgs()
                       ->andReturnSelf();

        $this->carbon->shouldReceive('create', 'day', 'copy', 'subDays', 'subMonth', 'addDay', 'format')
                     ->withAnyArgs()
                     ->andReturnSelf();

        $response = $this->invoiceRepository->invoiceByDueDate($this->carbon);

        $this->assertInstanceOf("App\Models\Payment", $response);

    }

    public function test_getInvoices_WhenCalledWith20210215_ReturnArrayValues(){
        $this->payment->shouldReceive('with', 'whereBetween', 'orderBy', 'get', 'sum', 'where', 'exists')
                      ->withAnyArgs()
                      ->andReturnSelf();

        $this->carbon->shouldReceive('create', 'day', 'copy', 'subDays', 'subMonth', 'addDay', 'format', 'today')
                     ->withAnyArgs()
                     ->andReturnSelf();

        $response = $this->invoiceRepository->getInvoices($this->carbon);

        $this->assertTrue(is_array($response));
    }
}
