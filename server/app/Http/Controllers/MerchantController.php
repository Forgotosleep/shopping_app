<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $loggedIn = Auth::user();  // Fetches logged-in user data
            $data = Merchant::where('user_id', $loggedIn->id)->get(['id', 'user_id', 'name', 'description', 'balance', 'slug', 'status']);
            if($data->isEmpty()) {  // Checks for whether User is registered as a Merchant or not
                return response()->json(\Response::error('Invalid role, not a merchant'), 400);
            }
            return response()->json(\Response::success('Merchant data fetch successful', $data), 200);
        } catch (\Throwable $e) {
            return response()->json(\Response::error('Failed to fetch merchant data', $e), 500);
        }
    }

    /**
     * Registers Current Logged-in User as Merchant
     */
    public function registerAsMerchant(Request $request)
    {
        try {
            $loggedIn = Auth::user();  // Fetches logged-in user data
            $slug = CommonHelpers::slugGenerator($request->name);

            // TODO Add Validation

            $merchantCheck = Merchant::where('user_id', $loggedIn->id)->first();
            if($merchantCheck) {
                return response()->json(\Response::error('Already a Merchant'), 400);
            }

            $newMerchant = Merchant::create([
                'user_id' => $loggedIn->id,
                'name' => $request->name,
                'description' => $request->description,
                'balance' => 0,
                'slug' => $slug,
                'status' => 'active'  // TODO defaults to active. In Production, should be 'inactive'.
            ]);

            if(!$newMerchant) {
                return response()->json(\Response::error('Merchant registration fail'), 400);
            }

            return response()->json(\Response::success('Merchant registration successful', $newMerchant->only('name', 'description', 'balance', 'slug', 'status')), 200);
        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchant $merchant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Merchant $merchant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Merchant $merchant)
    {
        //
    }
}
