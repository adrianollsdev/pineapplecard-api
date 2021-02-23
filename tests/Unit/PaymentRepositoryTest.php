<?php

namespace Tests\Unit;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;


class PaymentRepositoryTest extends TestCase
{
    public function mockeryTestSetUp()
    {
        $this->payment = m::mock('App\Models\Payment');
        $this->carbon = m::mock('Carbon\Carbon');
        $this->paymentRepository = new PaymentRepository($this->payment);
    }

    public function test_dateClosure_WhenCalledWithDueDate_ReturnCarbon()
    {
        $dateReturn = Carbon::create('2021-01-10');

        $this->carbon->shouldReceive('subDays')
            ->with(10)
            ->andReturn($dateReturn)
            ->once();

        $response = $this->paymentRepository->dateClosure($this->carbon);

        $this->assertInstanceOf("Carbon\Carbon", $response);
        $this->assertEquals($dateReturn, $response);
    }

    public function test_allPayments_whenCalledWithNothing_returnvaluePayment()
    {
        $this->payment->shouldReceive('with->where->get->all')
                      ->andReturn([]);

        $response = $this->paymentRepository->allPayments();

        $this->assertTrue(is_array($response));
    }

    public function test_getPointsCard_whenCalledWithNothing_returnvaluePoints()
    {
        $this->payment->shouldReceive('with->where->get->all')
                      ->andReturnSelf();

        $response = $this->paymentRepository->getPointsCard();

        $this->assertTrue(is_double($response));
    }

    public function test_limitAvailableCalculation_whenCalledWithDateCarbon_returnvalueArray()
    {
        $total_limit = 6000;
        $payment_day = 5;

        $this->payment->shouldReceive('max', 'where->sum')
                      ->andReturn(0.00);

        $this->carbon->shouldReceive('create', 'day', 'endOfDay', 'subDays', 'addMonth')
                     ->withAnyArgs()
                     ->andReturnSelf();

        $response = $this->paymentRepository->limitAvailableCalculation($this->carbon, $total_limit, $payment_day);

        $this->assertTrue(is_array($response));
    }

    public function test_valueOpenPayments_whenCalledWithInvoice_returnvalueOpenPayments()
    {
        $this->payment->shouldReceive('max', 'where->sum')
                      ->andReturn(0.00);

        $this->carbon->shouldReceive('create', 'day', 'endOfDay', 'subDays', 'addMonth')
                      ->withAnyArgs()
                      ->andReturnSelf();

        $dueDay = 5;

        $response = $this->paymentRepository->valueOpenPayments($this->carbon, $dueDay);

        $this->assertTrue(is_double($response));
    }

    public function test_invoicePeriod_WhenCalledWithDueDate20210215_Return2021010620210205()
    {
        $dueDate = new Carbon('2021-01-05');

        $this->carbon->shouldReceive('copy->subMonth', 'addDay', 'subDays', 'format')
                     ->andReturnSelf();


        $response = $this->paymentRepository->invoicePeriod($dueDate);

        $this->assertTrue(is_array($response));
    }

    public function test_invoiceByDueDate_WhenCalledWithAny_ReturnPayment()
    {
        $dueDate = new Carbon('2021-01-05');

        $this->payment->shouldReceive('with->whereBetween->orderBy->get->all')
                      ->andReturn([]);

        $this->carbon->shouldReceive('copy->subMonth', 'addDay', 'subDays', 'format')
                     ->withAnyArgs()
                     ->andReturnSelf();


        $response = $this->paymentRepository->invoiceByDueDate($dueDate);

        $this->assertTrue(is_object($response));
    }

    public function test_getValueTotalInvoices_WhenCalledInvoices_ReturnFloat()
    {
        $this->payment->shouldReceive('where->sum')
                      ->andReturn(0.00);

        $response = $this->paymentRepository->getValueTotalInvoices($this->payment);

        $this->assertTrue(is_double($response));
    }

    public function test_invoicePaid_WhenCalledWithRefMonthYear_ReturnBool()
    {
        $dueDate = new Carbon('2021-01-05');
        $refMonthYear = $dueDate->copy()->subMonth()->format('Y-m');

        $this->payment->shouldReceive('where->exists')
                      ->andReturn(false);

        $response = $this->paymentRepository->invoicePaid($refMonthYear);

        $this->assertTrue(is_bool($response));
    }

    public function test_invoiceClosure_WhenCalledWithRefMonthYear_ReturnBool()
    {
        $dueDate = new Carbon('2021-01-05');

        $this->carbon->shouldReceive('copy->today')
                      ->andReturnSelf();

        $response = $this->paymentRepository->invoiceClosure($dueDate);

        $this->assertTrue(is_bool($response));
    }

    public function test_getHeaderAllPeriods_WhenCalledWithCarbonAndDueDay_ReturnArray()
    {
        $payment_day = 10;


        $this->carbon->shouldReceive('copy->create->addMonth->day', 'copy->subMonth', 'addDay', 'subDays', 'format', 'copy->subMonth->format', 'copy->today')
                     ->andReturnSelf();


        $this->payment->shouldReceive('with', 'whereBetween', 'orderBy', 'get', 'all', 'where', 'exists', 'sum', 'min', 'max')
                      ->andReturnSelf();


        $response = $this->paymentRepository->getHeaderAllPeriods($this->carbon, $payment_day);

        $this->assertTrue(is_array($response));
    }

    public function test_getInvoices_WhenCalledWith20210215_ReturnArrayValues()
    {
        $dueDate = new Carbon('2021-01-05');

        $this->carbon->shouldReceive('copy->subMonth', 'addDay', 'subDays', 'format', 'copy->subMonth->format', 'copy->today')
                     ->andReturnSelf();

        $this->payment->shouldReceive('with', 'whereBetween', 'orderBy', 'get', 'all', 'where', 'exists', 'sum')
                      ->andReturnSelf();

        $response = $this->paymentRepository->getInvoices($dueDate);

        $this->assertTrue(is_object($response));
    }
}
