<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        app(\App\Repositories\Interfaces\PaymentRepositoryInterface::class);

        $products = Product::orderBy('id', 'desc')->get();

        $auth_id = auth()->id();
        $visitor_hash = session('v_hash');

        $cartItems = Cart::where('user_id', $auth_id)
            ->orWhere('visitor_hash', $visitor_hash)
            ->get();

        $productIdsInCart = $cartItems->pluck('product_id')->toArray();

        return view('home.index', compact('products', 'cartItems', 'productIdsInCart'));
    }


    public function terms()
    {
        $products = Product::orderBy('id', 'desc')->get();

        return view('terms_and_conditions', compact('products'));
    }
}
