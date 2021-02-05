<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;

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

    public function dateClosure(Carbon $dueDate, $qtdDays = 10)
    {
        // echo "\n[dayClosure] Starting...";

        return $dueDate->subDays($qtdDays);
    }

    public function valueOpenPayments(Carbon $dateCarbon, $dueDay){
        $lastRefPeriodPaid = $dateCarbon->create($this->payment->max('ref_month_year'))->day($dueDay);
        $lastDateClosurePaid = $this->dateClosure($lastRefPeriodPaid)->endOfDay();

        return $this->payment->where('created_at', '>', $lastDateClosurePaid)->sum('amount');
    }


}

