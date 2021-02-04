<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repositories\InvoiceRepository;
use App\Http\Resources\PaymentCollection;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile, $monthYear)
    {
        //
        $dueDate = Carbon::create($monthYear . "-" . strval($profile->payment_day))->addMonth();

        $payment = new Payment();

        $repository = new InvoiceRepository($payment);

        $invoicePayments = new PaymentCollection($repository->invoiceByDueDate($dueDate));
        $totalValueInvoice = $repository->valueTotalByInvoice($dueDate);

        return ["header" => $totalValueInvoice, "body" => $invoicePayments];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}