<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileCollection;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Profile;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;

class ProfileController extends Controller
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
    public function show(Profile $profile)
    {
        //
        $dateCarbon = new Carbon();
        $payment = new Payment();
        $paymentRepository = new PaymentRepository($payment);

        $limits = $paymentRepository->limitAvailableCalculation($dateCarbon->copy(), $profile->total_limit, $profile->payment_day);
        $points = $paymentRepository->getPointsCard();

        $headerAllPeriods = $paymentRepository->getHeaderAllPeriods($dateCarbon->copy(), $profile->payment_day);

        return response()->json([
                'limits' => $limits,
                'points' => $points,
                'profile' => $profile,
                'headers' => $headerAllPeriods
               ]);
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
