<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clipart;
use Illuminate\Support\Facades\Storage;

class ClipartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
        //
    public function index()
    {
        $cliparts = Clipart::all();
        return view('admin.cliparts.index', compact('cliparts'));
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

        $path = $request->file('image')->store('cliparts', 'public');

        Clipart::create([
            'image' => $path,
            'category' => $request->category,
        ]);

        return redirect()->route('admin.cliparts.index')->with('success', 'Clipart uploaded successfully!');
    }

    public function destroy(Clipart $clipart)
    {
        Storage::disk('public')->delete($clipart->image);
        $clipart->delete();

        return redirect()->route('admin.cliparts.index')->with('success', 'Clipart deleted successfully!');
    }
}
