<?php

namespace App\Repositories;

use App\Models\Payment;

class InvoiceRepository extends PaymentRepository
{

    // /**
    //  * @var Payment
    //  */

     private $payment;

     public function __construct(Payment $payment)
     {
        $this->payment = $payment;
     }

    public function invoicePeriod($dueDate){

        // echo "\n[invoicePeriod] Starting...";

        $initialDate = $this->dateClosure($dueDate->copy()->subMonth())->addDay();
        $finalDate   = $this->dateClosure($dueDate->copy());

        return array('initialDate' => $initialDate->format('Y-m-d'), 'finalDate' => $finalDate->format('Y-m-d'));
    }

    public function invoiceByDueDate($dueDate)
    {
        // echo "[InvoiceOpen] Starting...\n";
        $invoicePeriod = $this->invoicePeriod($dueDate);

        return $this->payment->with('establishment.establishmentCategory')
                                       ->whereBetween('created_at', array_values($invoicePeriod))
                                       ->orderBy('created_at', 'desc')
                                       ->get();
    }

    public function getInvoices($dueDate)
    {
        // echo "[valueTotalByInvoice] Starting...\n";
        $ref_month_year = $dueDate->copy()->subMonth()->format('Y-m');
        $invoices = $this->invoiceByDueDate($dueDate);

        $invoicePaid = $this->payment->where('ref_month_year', $ref_month_year)->exists();

        $invoiceClosure = $this->dateClosure($dueDate) < $dueDate->copy()->today() ? true : false;

        return ['header' => ['monthYearRef' => $ref_month_year,
                             'invoicePaid' => $invoicePaid,
                             'invoiceClosure' => $invoiceClosure,
                             'totalInvoice' => $invoices->sum('amount')
                            ],
                'body' => $invoices
                ];

    }

}

