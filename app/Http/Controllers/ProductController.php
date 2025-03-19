<?php

namespace App\Http\Controllers;


use App\Models\ProductColor;
use App\Models\Clipart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function create()
    {
        return view('admin.products.create');
    }

    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products')); // Update path
    }


    public function edit($id)
    {
        // Fetch the product normally
        $product = Product::findOrFail($id);
    
        // Fetch colors MANUALLY without relationships
        $colors = DB::table('product_colors')
                    ->where('product_id', $id)
                    ->get();
    
        // Debugging
         
    
        return view('admin.products.edit', compact('product', 'colors'));
    }
    

    
    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Validate request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'full_text' => 'nullable|string',
        'size' => 'nullable|string|max:50',
        'type' => 'nullable|string|max:50',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Update product details
    $product->update([
        'title' => $request->title,
        'description' => $request->description,
        'full_text' => $request->full_text,
        'size' => $request->size,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'type' => $request->type,
    ]);

    // ✅ Update Images
    foreach (['image1', 'image2', 'image3', 'image4'] as $imageField) {
        if ($request->hasFile($imageField)) {
            // Delete old image
            if ($product->$imageField) {
                Storage::delete('public/' . $product->$imageField);
            }
            // Save new image
            $product->$imageField = $request->file($imageField)->store('products', 'public');
        }
    }

    $product->save(); // Save updated images

    // ✅ Delete old colors before inserting new ones
    ProductColor::where('product_id', $product->id)->delete();

    // ✅ Insert new colors
    if ($request->has('colors')) {
        foreach ($request->colors as $color) {
            ProductColor::create([
                'product_id' => $product->id,
                'color_name' => $color['color_name'],
                'color_code' => $color['color_code'],
                'front_image' => isset($color['front_image']) && $color['front_image'] instanceof \Illuminate\Http\UploadedFile
                    ? $color['front_image']->store('colors', 'public')
                    : ($color['existing_front_image'] ?? null),
                'back_image' => isset($color['back_image']) && $color['back_image'] instanceof \Illuminate\Http\UploadedFile
                    ? $color['back_image']->store('colors', 'public')
                    : ($color['existing_back_image'] ?? null),
            ]);
        }
    }
    
    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
}



    public function store(Request $request)
    {
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'full_text' => $request->full_text,
            'size' => $request->size,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'image1' => $request->file('image1')->store('products', 'public'),
            'image2' => $request->file('image2') ? $request->file('image2')->store('products', 'public') : null,
            'image3' => $request->file('image3') ? $request->file('image3')->store('products', 'public') : null,
            'image4' => $request->file('image4') ? $request->file('image4')->store('products', 'public') : null,
        ]);

        if ($request->has('colors')) {
            foreach ($request->colors as $color) {
                ProductColor::create([
                    'product_id' => $product->id,
                    'color_name' => $color['color_name'],
                    'color_code' => $color['color_code'],
                    'front_image' => $color['front_image']->store('colors', 'public'),
                    'back_image' => isset($color['back_image']) && $color['back_image'] ? $color['back_image']->store('colors', 'public') : null,

                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully');
    }



    public function show($id)
    {
        $product = Product::with('colors')->find($id);
        $cliparts = Clipart::all(); // Ensure you fetch cliparts
        $product->load('colors'); // ✅ Force load colors manually

        return view('products.show', compact('product', 'cliparts'));
    }





    public function customize($id)
    {
        $product = Product::where('id', $id)->with('colors')->firstOrFail();
        // dd($product);
        $cliparts = Clipart::all();
        $productArray = $product->toArray();

        // dd($productArray);


        return view('products.customize', compact('productArray', 'cliparts', 'product'));
    }













    public function saveCustomization(Request $request, $id)
    {
        $request->validate([
            'custom_text' => 'nullable|string|max:255',
            'text_color' => 'nullable|string',
            'uploaded_image' => 'nullable|image|max:2048',
            'pre_made_image' => 'nullable|string',
        ]);

        $customData = [
            'custom_text' => $request->custom_text,
            'text_color' => $request->text_color,
            'uploaded_image' => null,
            'pre_made_image' => $request->pre_made_image,
        ];

        if ($request->hasFile('uploaded_image')) {
            $customData['uploaded_image'] = $request->file('uploaded_image')->store('customizations', 'public');
        }

        session()->put('custom_data_' . $id, $customData);

        return back()->with('success', 'Your design has been saved!');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}
