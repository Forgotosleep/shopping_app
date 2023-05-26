<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $loggedIn = \Auth::user();
            $cart = Cart::where('user_id', $loggedIn->id)->get();
            return response()->json(\Response::success('Cart fetch successful', $cart), 200);
        } catch (\Throwable $e) {
            
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $loggedIn = \Auth::user();
            $product = Product::find($request->idProduct);
            if(!$product = Product::find($request->idProduct)) {
                return response()->json(\Response::error('Product not found'), 404);
            }

            // Checks if product already exists in Cart or not
            $checkCart = Cart::where([
                ['user_id', $loggedIn->id],
                ['product_id', $request->idProduct]
            ]);
            if(!$checkCart[0]) {
                return response()->json(\Response::error('Product already exists in this User\'s Cart'), 400);
            }

            // Creates Product entry in Cart
            $cart = Cart::create([
                'trx_id' => $request->idTrx,
                'user_id' => $loggedIn->id,
                'product_id' => $product->id,
                'quantity' => $request->qty,
                'total_price' => ($request->qty * $product->price),
                'status' => 'active'
            ]);
            return response()->json(\Response::success('Add product to cart successful', $cart), 201);

        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        try {
            $loggedIn = \Auth::user();
            $cart = Cart::where([
                ['user_id', $loggedIn->id],
                ['id', $request->idCart]
            ])->first();
            if(!$cart) {
                return response()->json(\Response::error('Cart not found'), 404);
            }

            if($request->qty == 0) {
                $cart->delete();
            }
            else {
                $cart->update([
                    'quantity', $request->qty
                ]);            
            }
            return response()->json(\Response::success('Cart Product quantity modified successfully', $cart), 200);

        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    public function deleteItem(Request $request)
    {
        try {
            $loggedIn = \Auth::user();
            $cart = Cart::where([
                ['user_id', $loggedIn->id],
                ['id', $request->idCart]
            ])->first();
            if(!$cart) {
                return response()->json(\Response::error('Cart not found'), 404);
            }

            $cart->delete();            
            return response()->json(\Response::success('Cart Product deleted successfully', $cart), 200);
            
        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
