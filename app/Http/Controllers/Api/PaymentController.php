<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repositories\PaymentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentController extends Controller
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

    private function transformAnswers($answers, $offset, $perPage)
    {
        $answers = array_slice($answers, $offset, $perPage, true);

        return $this->transformer->toResult(collect($answers));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile, $monthYear, Request $request)
    {

        //
        $dueDate = Carbon::create($monthYear)->day($profile->payment_day)->addMonth();
        $payment = new Payment();
        $repository = new PaymentRepository($payment);

        $invoices = $repository->getInvoices($dueDate);

        /* $perPage = 10;
        $page = $request->input("page") ?? 1;

        $offset = ($page * $perPage) - $perPage;

        $invoices = array_slice($invoices, $offset, $perPage, true);

        $paginator = new LengthAwarePaginator(
                $this->transformer->toResult(collect($invoices)),
                count($invoices),
                $perPage,
                $page,
                ['path' => $this->request->url(), 'query' => $this->request->query()]
            ); */
        return response()->json($invoices);
        // return $paginator;
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
