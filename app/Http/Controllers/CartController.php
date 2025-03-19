<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    public function index()
    {
        $auth_id = auth()->id();
        $visitor_hash = session('v_hash');
        $cartItems = Cart::where('user_id', $auth_id)
            ->orWhere('visitor_hash', $visitor_hash)
            ->get();

        foreach ($cartItems as $item) {
            $item->product = Product::find($item->product_id);
        }

        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'front_image' => 'nullable|string',
            'back_image' => 'nullable|string',
        ]);

        $visitor_id = $request['v_hash'];
        $visitor = DB::table('visitors')->where('v_hash', $visitor_id)->first();
        $auth_user_id = auth()->id();

        if (!$visitor && !$auth_user_id) {
            if (request()->ajax) {
                return response()->json(['error' => 'Please login to add items to cart!'], 400);
            }
            return back()->with('error', 'Please login to add items to cart!');
        }

        try {
            DB::beginTransaction();

            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $auth_user_id ?? null,
                'visitor_hash' => $visitor_id ?? null,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => Product::find($request->product_id)->price * $request->quantity,
                'default_img' => $request->default_img ?? 1,
                'design_front_image' => null,
                'design_back_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($request->front_image || $request->back_image) {
                $imagePaths = $this->saveDesignImages($request, $cartId);

                if ($imagePaths) {
                    DB::table('carts')->where('id', $cartId)->update([
                        'design_front_image' => $imagePaths['front'] ?? null,
                        'design_back_image' => $imagePaths['back'] ?? null,
                    ]);
                }
            }

            DB::commit();

            if (request()->ajax) {
                return response()->json(['success' => 'Item added to cart successfully!'], 200);
            }

            return back()->with('success', 'Item added to cart successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart creation failed: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to add item to cart!'], 500);
            }
            return back()->with('error', 'Failed to add item to cart!');
        }
    }

    private function saveDesignImages(Request $request, int $cartId): ?array
    {
        try {
            $paths = [];

            if ($request->front_image) {
                $frontImageData = $this->cleanBase64Data($request->front_image);
                $frontImageName = $cartId . '_front.png';
                if (Storage::disk('public')->put('designs/' . $frontImageName, base64_decode($frontImageData))) {
                    $paths['front'] = 'designs/' . $frontImageName;
                }
            }

            if ($request->back_image) {
                $backImageData = $this->cleanBase64Data($request->back_image);
                $backImageName = $cartId . '_back.png';
                if (Storage::disk('public')->put('designs/' . $backImageName, base64_decode($backImageData))) {
                    $paths['back'] = 'designs/' . $backImageName;
                }
            }

            return !empty($paths) ? $paths : null;
        } catch (\Exception $e) {
            Log::error('Design image save failed: ' . $e->getMessage());
            return null;
        }
    }

    private function cleanBase64Data(string $base64Data): string
    {
        if (strpos($base64Data, ';base64,') !== false) {
            $base64Data = explode(';base64,', $base64Data)[1];
        }
        return $base64Data;
    }


    public function destroy($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->delete();
            return response()->json(['success' => 'Item removed from cart successfully!'], 200);
        } else {
            return response()->json(['error' => 'Failed to remove item from cart!'], 400);
        }
    }

    public function clear()
    {

        $auth_id = auth()->id();
        $visitor_hash = session('v_hash');

        if (!$auth_id && !$visitor_hash) {
            return response()->json(['error' => 'Please login to remove items from cart!'], 400);
        }

        $cartItems = Cart::where('user_id', $auth_id)
            ->orWhere('visitor_hash', $visitor_hash)
            ->get();

        foreach ($cartItems as $item) {
            $item->delete();
        }

        return response()->json(['success' => 'All items removed from cart successfully!'], 200);
    }

    public function show($id)
    {

        $cart_item = DB::table('carts')->where('id', $id)->first();

        $images = (object) [];
        $images->first_image = Storage::url($cart_item->design_front_image);
        $images->second_image = Storage::url($cart_item->design_back_image);

        return view('cart.show', ['images' => $images]);
    }
}
