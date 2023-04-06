<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Transactions;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index()
    // {
    //     return view('client.payments.payment');
    // }

    public function singleChargePayment(Request $request)
    {
        $amountToCharge = intval($request->amount) * 100;
        $stripeCharge = $request->user()->charge(
            $amountToCharge, $request->paymentMethod['id']
        );
        
        $now = new \DateTime();


        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/stripe/'.$now->format('Y-m-d') .'.log'),
          ])->info($stripeCharge);

        return $stripeCharge;
    }
    public function saveTransaction(Request $request)
    {        
        $transaction = new Transactions();
        $transaction->user_id = Auth::id();
        $transaction->type = $request->type;
        $transaction->project_id = $request->project_id;
        if($request->bbb_meeting_id)$transaction->bbb_meeting_id = $request->bbb_meeting_id;
        $transaction->amount = $request->amount;
        $transaction->stripe_id = $request->stripe_id;
        $transaction->status = $request->status;
        $transaction->save();
        return $transaction;
    }
}
