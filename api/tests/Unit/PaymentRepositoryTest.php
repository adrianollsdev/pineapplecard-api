<?php

namespace Tests\Unit;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use App\Repositories\PaymentRepository;

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

        $this->carbon->shouldReceive('create', 'day', 'endOfDay', 'subDays')
            ->withAnyArgs()
            ->andReturnSelf();

        $response = $this->paymentRepository->dateClosure($this->carbon);

        $this->assertInstanceOf("Carbon\Carbon", $response);
    }

    public function test_valueOpenPayments_whenCalledWithInvoice_returnvalueOpenPayments()
    {
        $this->payment->shouldReceive('max', 'where', 'sum')
                      ->withAnyArgs()
                      ->andReturnSelf();

        $this->carbon->shouldReceive('create', 'day', 'endOfDay', 'subDays', 'addMonth')
                      ->withAnyArgs()
                      ->andReturnSelf();

        $dueDay = 5;

        $response = $this->paymentRepository->valueOpenPayments($this->carbon, $dueDay);

        $this->assertInstanceOf("App\Models\Payment", $response);
    }
}
