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

    function displayProducts($trxId) {
        /* This function takes a trxId and returns an array of product that is contained in that particular TRX. If the TRX does not exist, it returns an empty array */
        $data = [];
        $carts = Cart::where('trx_id', $trxId)->get(['id', 'product_id']);

        if(!empty($carts)) {
            foreach ($carts as $cart) {
                array_push($data, collect($cart->product)->except('deleted_at', 'created_at', 'updated_at'));
            }            
        }

        return $data;
    }
    
    public function listByUser()
    {
        try {
            $loggedIn = Auth::user();
            $trxs = Transaction::where('user_id', $loggedIn->id)->get(['id', 'user_id', 'total_price', 'cart_ids', 'status']);
            if(!empty($trxs)) {
                collect($trxs)->map(function($trx) {
                    $trx->products = self::displayProducts($trx->id);
                    return $trx;
                });
            }
            return response()->json(\Response::success('Transaction fetch successful', $trxs), 200);
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
                ['selected', true],
                ['trx_id', null]
            ])->get();

            $cartIDs = [];  // container for all of the SELECTED Cart's IDs
            $totalPrice = 0;
            foreach ($carts as $cart) {
                array_push($cartIDs, $cart->id);
                $totalPrice += $cart->price;
            }

            // TODO Insert TRX creation by using Cart as its base. Remember to ADD IN TRANSACTION ID for the affected Cart!
            $trx = Transaction::create([
                'user_id' => $loggedIn->id,
                'cart_ids' => json_encode($cartIDs),
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Fill in all the Cart's trx_id with the newly created 'trx' id, this transforms the Carts into a Transaction and won't be selected in the future.
            collect($carts)->transform(function ($cart) use($trx) {
                $cart->trx_id = $trx->id;
                $cart->update();
            });

            DB::commit();  // Finalizes the TRX Creation & Cart update

            return response()->json(\Response::success('Transaction creation successful', $trx), 201);

        } catch (\Throwable $e) {
            DB::rollBack();  // Revert changes if something goes wrong
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    public function updateTrxStatus(Request $request) {
        // Changes the Transaction's status to be updated accordingly
    }
}
