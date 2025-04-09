<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            url('/'),
            url('/products'),
            url('/terms'),
        ];

        $products = Product::all();

        $productUrls = $products->map(function ($product) {
            return [
                'loc' => url("/products/{$product->id}/customize"),
                'lastmod' => $product->updated_at->toAtomString(),
            ];
        });

        return response()->view('sitemap', [
            'urls' => $urls,
            'productUrls' => $productUrls,
        ])->header('Content-Type', 'application/xml');
    }
}

