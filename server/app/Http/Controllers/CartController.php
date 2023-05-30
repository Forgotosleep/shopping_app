<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Merchant;
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
            $cart = Cart::where([
                ['user_id', $loggedIn->id],
                ['trx_id', null]  // No transaction ID means the Cart is not finalized into a Transaction yet
            ])->get();
            return response()->json(\Response::success('Cart fetch successful', $cart), 200);
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
            $loggedIn = \Auth::user();
            $product = Product::find($request->idProduct);
            if(!$product = Product::find($request->idProduct)) {
                return response()->json(\Response::error('Product not found'), 404);
            }

            // Checks if product already exists in Cart or not
            $checkCart = Cart::where([
                ['user_id', $loggedIn->id],
                ['product_id', $request->idProduct]
            ])->first();

            if($checkCart) {
                // TODO Consider returning an error or adding quantity of the existing product in the cart
                return response()->json(\Response::error('Product already exists in this user\'s Cart'), 400);
            }

            // Merchant Check
            $isMerchant = Merchant::where('user_id', $loggedIn->id)->first();
            if($isMerchant && $product->merchant_id == $isMerchant->id) {
                return response()->json(\Response::error('Please don\'t buy your own product'), 400);
            }

            // Creates Product entry in Cart
            $cart = Cart::create([
                'user_id' => $loggedIn->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => ($request->quantity * $product->price),
            ]);

            $currentCart = $cart->where('user_id', $loggedIn->id)->get(['id', 'product_id', 'quantity', 'price']);  // Gets User's current Cart
            return response()->json(\Response::success('Add product to cart successful', $currentCart), 201);

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
                ['id', $request->idCart],
                ['user_id', $loggedIn->id],
                ['transaction_id', null]  // No transaction ID means the Cart is not finalized into a Transaction yet
            ])->first();

            $cart = Cart::find($request->idCart);

            if(!$cart) {
                return response()->json(\Response::error('Cart not found'), 404);
            }

            if($request->quantity == 0) {
                $cart->delete();
            }
            else {
                $singleProductPrice = $cart->price / $cart->quantity;  // Made this to avoid another SQL query. But if the formula for 'price' in tampered with (e.g. inserted not as PURE PRODUCT PRICE, but after discount or smth, this will become unreliable. Something to think about)
                $cart->quantity = $request->quantity;
                $cart->price = $singleProductPrice * $request->quantity;
                $cart->save(); 
            }
            return response()->json(\Response::success('Cart Product quantity modified successfully', $cart), 200);

        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    public function selectItem(Request $request)
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

            // Changes the specified Cart selected status to 'true'. It will be included when the user's making a transaction.
            $cart->selected = true;
            $cart->save();
            return response()->json(\Response::success('Cart selected successfully', $cart->only('id', 'product_id', 'price', 'quantity', 'selected')), 200);
            
        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }
    
    public function unselectItem(Request $request)
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

            // Changes the specified Cart selected status to 'false'. It will be excluded when the user's making a transaction.
            $cart->selected = false;
            $cart->save();
            return response()->json(\Response::success('Cart unselected successfully', $cart->only('id', 'product_id', 'price', 'quantity', 'selected')), 200);
            
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
