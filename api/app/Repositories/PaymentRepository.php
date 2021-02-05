<?php

namespace App\Repositories;

use App\Repositories\InvoiceRepository;

class PaymentRepository
{
    /**
     * @var Payment
     */

    private $invoice;

    public function __construct(InvoiceRepository $invoice)
    {
        $this->invoice = $invoice;
    }

    public function openPayments(){

    }


}

