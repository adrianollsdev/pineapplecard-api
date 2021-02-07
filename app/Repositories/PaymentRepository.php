<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;

class PaymentRepository
{
    /**
     * @var Payment
     */

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function dateClosure($dueDate, $qtdDays = 10)
    {
        return $dueDate->subDays($qtdDays);
    }

    public function allPayments()
    {
        return ($this->payment->with('establishment.establishmentCategory')->where('type', '<>', 'P')->get())->all();
    }

    public function getPointsCard()
    {
        $points = 0;
        $invoiceReleases = $this->allPayments();

        foreach ($invoiceReleases as $invoiceRelease) {
            $points += $invoiceRelease->amount *
                       $invoiceRelease->establishment->establishmentCategory->score_rate;
        }

        return round($points, 2);
    }

    public function limitAvailableCalculation($dateCarbon, $total_limit, $payment_day)
    {
        $limits = [];
        $limitUsed = $this->valueOpenPayments($dateCarbon, $payment_day);
        $limitAvailable = $total_limit - $limitUsed;

        if (!empty($limitUsed) && !empty($limitAvailable)) {
            $limits =  ['limitAvailable' => round($limitAvailable, 2), 'limitUsed' => round($limitUsed, 2)];
        }
        return $limits;
    }

    public function valueOpenPayments(Carbon $dateCarbon, $dueDay){
        $lastRefPeriodPaid = $dateCarbon->create($this->payment->max('ref_month_year'))->addMonth()->day($dueDay);
        $lastDateClosurePaid = $this->dateClosure($lastRefPeriodPaid)->endOfDay();

        return doubleval($this->payment->where('created_at', '>', $lastDateClosurePaid)->sum('amount'));
    }

    public function invoicePeriod($dueDate)
    {

        $initialDate = $this->dateClosure($dueDate->copy()->subMonth()->addDay());
        $finalDate   = $this->dateClosure($dueDate->copy());

        return array('initialDate' => $initialDate->format('Y-m-d'), 'finalDate' => $finalDate->format('Y-m-d'));
    }

    public function invoiceByDueDate($dueDate)
    {
        $invoicePeriod = $this->invoicePeriod($dueDate);

        return $this->payment->with('establishment.establishmentCategory')
                             ->whereBetween('created_at', array_values($invoicePeriod))
                             ->orderBy('created_at', 'desc')
                             ->get();
    }

    public function getValueTotalInvoices($invoices)
    {
        return $invoices->where('type', '<>', 'P')->sum('amount');
    }

    public function invoicePaid($refMonthYear){
        return $this->payment->where('ref_month_year', $refMonthYear)->exists();
    }

    public function invoiceClosure($dueDate){
        return $this->dateClosure($dueDate) < $dueDate->copy()->today() ? true : false;
    }

    public function getInvoices($dueDate)
    {
        $refMonthYear = $dueDate->copy()->subMonth()->format('Y-m');
        $invoices = $this->invoiceByDueDate($dueDate);

        $invoicePaid = $this->invoicePaid($refMonthYear);

        $invoiceClosure = $this->invoiceClosure($dueDate);

        $totalInvoices = $this->getValueTotalInvoices($invoices);

        return [
            'header' => [
                'monthYearRef' => $refMonthYear,
                'invoicePaid' => $invoicePaid,
                'invoiceClosure' => $invoiceClosure,
                'totalInvoice' => $totalInvoices
            ],
            'body' => $invoices
        ];
    }


}

