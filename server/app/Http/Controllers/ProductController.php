<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = Product::where([['products.status', 'active']]);

            if (trim($request->query("name"))) {
                $searchTexts = preg_split('/\s+/', trim($request->query("name")), -1, PREG_SPLIT_NO_EMPTY);
                $products = $products->where(function ($q2) use ($searchTexts) {
                    foreach ($searchTexts as $searchText) {
                        $q2->where('products.name', 'like', "%{$searchText}%");
                    }
                });
            }

            if ($request->query("merchant")) {  // TODO Switch to Merchant's UUID instead
                $products = $products->where("products.merchant_id", $request->query("merchant"));
            }
            if ($request->query("min_price")) {
                $products = $products->where("price", ">=", $request->query("min_price"));
            }
            if ($request->query("max_price")) {
                $products = $products->where("price", "<=", $request->query("max_price"));
            }

            // SORT
            $sort_type = $request->query("sortby");
            switch ($sort_type) {
                case "highest_price":
                    $products = $products->orderBy("price", "desc");
                    break;
                case "lowest_price":
                    $products = $products->orderBy("price", "asc");
                    break;
                case "a_z":
                    $products = $products->orderBy("products.name", "asc");
                    break;
                case "z_a":
                    $products = $products->orderBy("products.name", "desc");
                    break;
                default:
                    // TODO terlaris
                    break;
            }

            $query = $products
                ->leftJoin('merchants', 'products.merchant_id', '=', 'merchants.id')
                ->select(
                    'merchants.name as merchant_name',
                    "products.*",
                )->where('merchants.status', '=', 'active');

            if (!$request->input('limit')) {
                $products = $query->paginate(30);
            } else {
                $products = $query->paginate($request->input('limit'));
            }

            return $products;
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
            // TODO Switch Current Logged-in is Merchant as Middleware instead
            $loggedIn = Auth::user();
            if(!$merchant = Merchant::where('user_id', $loggedIn->id)->first()) {
                return response()->json(\Response::error('Unauthorized. Not a Merchant'), 401);
            }

            $newProduct = Product::create([
                'merchant_id' => $merchant->id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'status' => $request->status ?? 'active'
            ]);
            return response()->json(\Response::success('Product creation successful', $newProduct->only('name', 'description', 'price', 'status')), 201);

        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // TODO Switch Current Logged-in is Merchant as Middleware instead
            $loggedIn = Auth::user();
            if(!$merchant = Merchant::where('user_id', $loggedIn->id)->first()) {
                return response()->json(\Response::error('Unauthorized. Not a Merchant'), 401);
            }
            if(!$product = Product::find($request->idProduct)) {
                return response()->json(\Response::error('Product not found'), 404);
            }
            if($merchant && $product && $product->merchant_id != $merchant->id) {
                return response()->json(\Response::error('Not your product'), 401);
            }

            $product->update([
                'name' => $request->name ?? $product->name,
                'description' => $request->description ?? $product->description,
                'price' => $request->price ?? $product->price,
                'status' => ($request->status != 'active' && $request->status != 'inactive' ? $product->status : $request->status),
            ]);
            return response()->json(\Response::success('Product update successful', $product->only('name', 'description', 'price', 'status')), 200);

        } catch (\Throwable $e) {
            return response()->json(\Response::error('Internal Server Error', $e), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
