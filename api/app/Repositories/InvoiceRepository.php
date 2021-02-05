<?php

namespace App\Repositories;

use App\Models\Payment;

class InvoiceRepository
{

    /**
     * @var Payment
     */

     private $payment;

     public function __construct(Payment $payment)
     {
        $this->payment = $payment;
     }

    public function dayClosure($dueDate, $qtdDays = 10)
    {
        // echo "\n[dayClosure] Starting...";

        return $dueDate->subDays($qtdDays);
    }

    public function invoicePeriod($dueDate){

        // echo "\n[invoicePeriod] Starting...";

        $initialDate = $this->dayClosure($dueDate->copy()->subMonth())->addDay();
        $finalDate   = $this->dayClosure($dueDate->copy());

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

    public function valueTotalByInvoice($dueDate)
    {
        // echo "[valueTotalByInvoice] Starting...\n";
        $invoices = $this->invoiceByDueDate($dueDate);

        return array('monthYearRef' => $dueDate->copy()->subMonth()->format('Y-m'), 'totalInvoice' => $invoices->sum('amount'));

    }

}

