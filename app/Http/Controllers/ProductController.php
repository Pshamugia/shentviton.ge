<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Clipart;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage; 




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


    public function show($id)
    {
        $product = Product::with('colors')->find($id);
        $cliparts = Clipart::all(); // Ensure you fetch cliparts
        $product->load('colors'); // ✅ Force load colors manually
        $productArray = $product->toArray();

        
        return view('products.show', compact('product', 'cliparts', 'productArray'));
    }

 

    public function showByType($type)
    {
        $slugToType = [
            't-shirt' => 'მაისური',
            'hoodie' => 'ჰუდი',
            'phone-case' => 'ქეისი',
            'cap' => 'კეპი',
            'polo' => 'პოლო',
        ];
    
        $typeSlug = $type;
        $type = $slugToType[$typeSlug] ?? $typeSlug;
    
        $subtype = request()->query('subtype', 'მზა');
        $sort = request()->query('sort', 'newest');
        $selectedSize = request()->query('size');
    
        $query = Product::where('subtype', $subtype);
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
    
        // 👉 Clone query before applying size filter
        $sizeQuery = (clone $query);
    
        if ($selectedSize) {
            $query->where('size', 'LIKE', '%' . $selectedSize . '%');
        }
    
        // Sorting
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    
        $products = $query->paginate(9)->withQueryString();
    
        // 🛒 Cart logic
        $auth_id = auth()->id();
        $visitor_hash = session('v_hash');
    
        $cartItems = Cart::where('user_id', $auth_id)
            ->orWhere('visitor_hash', $visitor_hash)
            ->get();
    
        $productIdsInCart = $cartItems->pluck('product_id')->toArray();
    
        // ✅ Now use the unfiltered query to get all available sizes
        $allSizes = $sizeQuery->pluck('size')
            ->filter()
            ->flatMap(function ($sizes) {
                return array_map('trim', explode(',', $sizes));
            })
            ->unique()
            ->sort()
            ->values();
    
        return view('products.by_type', compact(
            'products',
            'type',
            'subtype',
            'sort',
            'cartItems',
            'productIdsInCart',
            'allSizes',
            'selectedSize'
        ));
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
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'full_text' => 'nullable|string',
            'size' => 'nullable|array',
            'size.*' => 'string|max:50',
            'type' => 'nullable|string|max:50',
            'subtype' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
    
        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'full_text' => $request->full_text,
            'size' => is_array($request->size) ? implode(',', $request->size) : $request->size,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'subtype' => $request->subtype,
        ]);
    
        foreach (['image1', 'image2', 'image3', 'image4'] as $imageField) {
            if ($request->hasFile($imageField)) {
                if ($product->$imageField) {
                    Storage::delete('public/' . $product->$imageField);
                }
                $product->$imageField = $this->convertToWebP($request->file($imageField), 'products');
            }
        }
    
        $product->save();
    
        ProductColor::where('product_id', $product->id)->delete();
    
        if ($request->has('colors')) {
            foreach ($request->colors as $index => $color) {
                $frontFile = $request->file("colors.$index.front_image");
                $backFile = $request->file("colors.$index.back_image");
    
                $frontImage = $frontFile ? $this->convertToWebP($frontFile, 'colors') : ($color['existing_front_image'] ?? null);
                $backImage = $backFile ? $this->convertToWebP($backFile, 'colors') : ($color['existing_back_image'] ?? null);
    
                ProductColor::create([
                    'product_id' => $product->id,
                    'color_name' => $color['color_name'],
                    'color_code' => $color['color_code'],
                    'front_image' => $frontImage,
                    'back_image' => $backImage,
                ]);
            }
        }
    
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }



    

public function store(Request $request)
{
    $image1 = $request->hasFile('image1') ? $this->convertToWebP($request->file('image1'), 'products') : null;
    $image2 = $request->hasFile('image2') ? $this->convertToWebP($request->file('image2'), 'products') : null;
    $image3 = $request->hasFile('image3') ? $this->convertToWebP($request->file('image3'), 'products') : null;
    $image4 = $request->hasFile('image4') ? $this->convertToWebP($request->file('image4'), 'products') : null;

    $product = Product::create([
        'title' => $request->title,
        'description' => $request->description,
        'full_text' => $request->full_text,
        'size' => is_array($request->size) ? implode(',', $request->size) : $request->size,
        'type' => $request->type,
        'subtype' => $request->subtype,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'image1' => $image1,
        'image2' => $image2,
        'image3' => $image3,
        'image4' => $image4,
    ]);

    if ($request->has('colors')) {
        foreach ($request->colors as $color) {
            $frontImage = isset($color['front_image']) && $color['front_image'] instanceof \Illuminate\Http\UploadedFile
                ? $this->convertToWebP($color['front_image'], 'colors')
                : null;

            $backImage = isset($color['back_image']) && $color['back_image'] instanceof \Illuminate\Http\UploadedFile
                ? $this->convertToWebP($color['back_image'], 'colors')
                : null;

            ProductColor::create([
                'product_id' => $product->id,
                'color_name' => $color['color_name'],
                'color_code' => $color['color_code'],
                'front_image' => $frontImage,
                'back_image' => $backImage,
            ]);
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Product added successfully');
}





private function convertToWebP($file, $folder)
{
    $extension = strtolower($file->getClientOriginalExtension());
    $path = $file->getPathname();
    $mime = $file->getMimeType();

    logger()->info("Converting file", [
        'extension' => $extension,
        'mime' => $mime,
        'original_name' => $file->getClientOriginalName(),
    ]);

    switch ($extension) {
        case 'jpeg':
        case 'jpg':
            $src = @imagecreatefromjpeg($path);
            break;
        case 'png':
            $src = @imagecreatefrompng($path);
            break;
        case 'gif':
            $src = @imagecreatefromgif($path);
            break;
        case 'webp':
            $src = @imagecreatefromwebp($path);
            break;
        default:
            logger()->error("Unsupported format: " . $extension);
            return $file->store($folder, 'public');
    }

    if (!$src) {
        logger()->error("Image creation failed. Storing original instead.");
        return $file->store($folder, 'public');
    }

    $filename = Str::uuid() . '.webp';
    $storagePath = storage_path("app/public/{$folder}/{$filename}");

    imagepalettetotruecolor($src);
    imagealphablending($src, true);
    imagesavealpha($src, true);

    imagewebp($src, $storagePath, 80);
    imagedestroy($src);

    logger()->info("Image saved to WebP: $storagePath");

    return "{$folder}/{$filename}";
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
