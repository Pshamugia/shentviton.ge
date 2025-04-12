<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Enums\CartStatus;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();

        $authId = auth()->id();
        $visitorHash = session('v_hash');

        $query = Cart::query();

        if ($authId) {
            $query->where(function ($subquery) use ($authId, $visitorHash) {
                $subquery->where('user_id', $authId);

                if ($visitorHash) {
                    $subquery->orWhere('visitor_hash', $visitorHash);
                }
            });
        } elseif ($visitorHash) {
            $query->where('visitor_hash', $visitorHash);
        } else {
            $query->where('id', 0);
        }

        $cartItems =  $query->where('status', CartStatus::PENDING)->get();

        $productIdsInCart = $cartItems->pluck('product_id')->toArray();

        $readyDesigns = Product::where('subtype', 'მზა')->orderBy('id', 'desc')->take(9)->get(); // მზა დიზაინებისთვის
        $customDesigns = Product::where('subtype', 'custom')->orderBy('id', 'desc')->take(9)->get(); // custom დიზაინების გამოსატანად

        return view('home.index', compact('readyDesigns', 'customDesigns', 'cartItems', 'productIdsInCart'));
    }



    public function terms()
    {
        $products = Product::orderBy('id', 'desc')->get();

        return view('terms_and_conditions', compact('products'));
    }
}
