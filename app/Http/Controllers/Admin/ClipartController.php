<?php

namespace App\Http\Controllers\Admin;

use App\Models\Clipart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClipartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
        //
        public function loadMore(Request $request)
        {
            $offset = (int) $request->input('offset', 0);
            $limit = 10;
            $category = $request->input('category', 'all');
        
            $query = \App\Models\Clipart::query();
        
            if ($category !== 'all') {
                $query->where('category', $category);
            }
        
            // clone for accurate count
            $total = (clone $query)->count();
        
            $cliparts = $query
                ->orderBy('id', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
        
            $html = '';
            foreach ($cliparts as $clipart) {
                if ($clipart->image) {
                    $html .= '<div class="clipart-item">';
                    $html .= '<img class="clipart-img" data-category="' . $clipart->category . '"';
                    $html .= ' data-image="' . asset('storage/' . $clipart->image) . '"';
                    $html .= ' src="' . asset('storage/' . $clipart->image) . '"';
                    $html .= ' alt="Clipart" loading="lazy">';
                    $html .= '</div>';
                }
            }
        
            return response()->json([
                'html' => $html,
                'hasMore' => ($offset + $limit) < $total, // ✅ correct logic now
            ]);
        }


    public function index()
    {
        $cliparts = Clipart::paginate(12);
        return view('admin.cliparts.index', compact('cliparts'));
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

    public function create()
    {
        return view('admin.cliparts.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        'category' => 'required|string'
    ]);

    // Convert and store as WEBP using your custom method
    $webpPath = $this->convertToWebP($request->file('image'), 'cliparts');

    // Save clipart record
    Clipart::create([
        'image' => $webpPath,
        'category' => $request->category,
    ]);

    return redirect()->route('admin.cliparts.index')->with('success', 'Clipart uploaded successfully as WebP!');
}



public function edit(Clipart $clipart)
{
    return view('admin.cliparts.edit', compact('clipart'));
}

public function update(Request $request, Clipart $clipart)
{
    $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'category' => 'required|string'
    ]);

    // Delete old image if new one is uploaded
    if ($request->hasFile('image')) {
        Storage::disk('public')->delete($clipart->image);
        $webpPath = $this->convertToWebP($request->file('image'), 'cliparts');
        $clipart->image = $webpPath;
    }

    $clipart->category = $request->category;
    $clipart->save();

    return redirect()->route('admin.cliparts.index')->with('success', 'Clipart updated successfully!');
}


    public function destroy(Clipart $clipart)
    {
        Storage::disk('public')->delete($clipart->image);
        $clipart->delete();

        return redirect()->route('admin.cliparts.index')->with('success', 'Clipart deleted successfully!');
    }
}
