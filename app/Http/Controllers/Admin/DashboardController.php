<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use App\Models\Payment;
use App\Models\Product;
use App\Enums\CartStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
    
        // Inventory-based data (Product model)
        $totalProductValue = Product::sum(DB::raw('price * quantity'));
        $avgProductPrice = Product::average('price');
        $productCount = Product::count();
       
    

    
        // Sold product data (Cart model)
        $cartQuery = Cart::where('status', CartStatus::PAID);
    
        if ($start && $end) {
            $cartQuery->whereBetween('created_at', [$start, $end]);
        }
    
        $totalSoldAmount = $cartQuery->sum('total_price');
        $avgPricePerItem = $cartQuery->average('total_price');
        $totalQuantity = $cartQuery->sum('quantity');

        
    
        return view('admin.dashboard', [
            'totalProductValue' => number_format($totalProductValue, 2),
            'avgProductPrice'   => number_format($avgProductPrice, 2),
            'productCount'      => $productCount,
            'totalSoldAmount'   => number_format($totalSoldAmount, 2),
            'avgPricePerItem'   => number_format($avgPricePerItem, 2),
            'totalQuantity'     => $totalQuantity,
             'startDate'         => $start,
            'endDate'           => $end,
            
        ]);
    }
}