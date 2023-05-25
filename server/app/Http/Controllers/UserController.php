<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(\Response::success_without_data("This is Home"), 200);
    }

    /**
     * Login function for User
     */
    public function login(Request $request) {
        try {
            $credentials = $request->only('email', 'password');

            $user = User::where([
                ['email', $credentials['email']],
                ['status', 'active'] 
            ])->withTrashed()->first();

            error_log('HERE!');
            error_log($user);
            // error_log(Hash::check($credentials['password'], $user->password));
            error_log($user && Hash::check($credentials['password'], $user->password));
            // error_log(Auth::guard('api')->attempt(['password' => $credentials['password'], 'email' => $credentials['email']]));

            if(!$user || $user->deleted_at) {  // Login Failed
                return response()->json(\Response::error_without_data("Invalid credentials", ["error" => "Invalid credentials"]), 400);
            }
            elseif($user && Hash::check($credentials['password'], $user->password)) {  // Login Success
                // $data = Auth::guard('web')->attempt($credentials);  // Laravel Session
                // Auth::login($user);
                // error_log('WHER!!!');
                // error_log($data);
                $token = $user->createToken('web');
                return response()->json(\Response::success("Login Successful", [
                    "token" => $token,
                    "data" => Auth::user()
                ]), 200);
            }
            else {  // Password wrong
                return response()->json(\Response::error_without_data("Invalid credentials", ["error" => "Invalid credentials"]), 400);
            }

        } catch (\Throwable $e) {
            return response()->json(\Response::error('Login failed', $e), 500);
        }
    }    

    public function logout(Request $request) {
        try {
            if($checkUser = Auth::user()) {
                $checkUser->currentAccessToken()->delete();
            }; 
            return response()->json(\Response::success_without_data('Logout successful'), 200);        
        } catch (\Throwable $e) {
            return response()->json(\Response::error('Failed to logout', $e), 500);
        }
    }

    /**
     * Registers a new User
     */
    public function register(Request $request)
    {
        /*
            Params (Request)
              'name',
              'email',
              'phone',
              'password

        */
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => ['required', 'numeric', 'regex:#[\d]{8,12}#'],
            'password' => 'required|string|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
        ]);
        /*
        Password validator:
          - Minimum 8 chars
          - Minimum of one Uppercase letter
          - Minimum of one lowercase letter
          - Minimum of one special character/symbol

        Phone validator:
          - Should start with with '8'
          - Should be all numbers
        */

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'error' => $validator->errors()
            ), 400);
        }

        /* Phone Number Trimmer. Gotta keep em slick and in the correct format! */
        /* For those who still sends us phone number starting with +628lalala, 628lalala and 08lalala */
        $newPhone = CommonHelpers::phoneTrimmer($request->phone);

        $user = User::where('email', $request->email)
            ->orWhere('phone', $newPhone)
            ->first();

        // // User Activation
        // if ($user && ($user->status == 'active')) {
        //     return response()->json(\Response::error_without_data("This account already exists"), 400);
        // } else if ($user && $user->status == 'inactive') {
        //     $user->otp = mt_rand(100000, 999999);  // Activation Code is now six digits between 100000 and 999999
        //     $user->save();

        //     return response()->json(\Response::success("Activation code is sent. Please check your Whatsapp"));
        // }

        $user = new User();
        $user = $user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $newPhone,
            'otp' => mt_rand(100000, 999999)
        ]);

        return response()->json(\Response::success("Success register", $user), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
