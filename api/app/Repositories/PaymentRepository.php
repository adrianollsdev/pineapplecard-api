<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    /**
     * @var Payment
     */

    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}

