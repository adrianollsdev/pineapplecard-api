<?php

namespace Tests\Unit;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use App\Repositories\ProfileRepository;

class PorfileRepositoryTest extends TestCase
{
    public function mockeryTestSetUp()
    {
        $this->paymentRepository = m::mock('App\Repositories\PaymentRepository');
        $this->profile = m::spy('App\Models\Profile');
        $this->carbon = m::mock('Carbon\Carbon');
        $this->profileRepository = new ProfileRepository($this->profile, $this->paymentRepository);
    }

    public function test_limitAvailableCalculation_whenCalledWithDateCarbon_returnvalueArray()
    {
        $this->paymentRepository->shouldReceive('valueOpenPayments')
                      ->withAnyArgs()
                      ->andReturn();

        $response = $this->profileRepository->limitAvailableCalculation($this->carbon);

        $this->assertTrue(is_array($response));
    }
}
