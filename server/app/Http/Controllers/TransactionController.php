<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\Cart;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listByUser()
    {
        try {
            $loggedIn = Auth::user();
            $trx = Transaction::where('user_id', $loggedIn->id)->get();
            return response()->json(\Response::success('Transaction fetch successful', $trx->only('id', 'user_id', 'merchant_id', 'total_price', 'status')), 200);
        } catch (\Throwable $e) {
            
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    public function listByMerchant() {
        try {
            $loggedIn = Auth::user();
            if(!$merchant = Merchant::where('user_id', $loggedIn->id)->first()) {
                return response()->json(\Response::error('Unauthorized. Not a Merchant'), 401);
            }
            $trx = Transaction::where('user_id', $merchant->id)->get();
            return response()->json(\Response::success('Transaction fetch successful', $trx->only('id', 'user_id', 'merchant_id', 'total_price', 'status')), 200);
        } catch (\Throwable $e) {
            
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $loggedIn = Auth::user();

            DB::beginTransaction();
            $carts = Cart::where([
                ['user_id', $loggedIn->id],
                ['selected', true]
            ]);
            // TODO Insert TRX creation by using Cart as its base. Remember to ADD IN TRANSACTION ID for the affected Cart!

            

            DB::commit();
            return response()->json(\Response::success('Transaction creation successful'), 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
