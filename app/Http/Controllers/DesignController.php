<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function preview($key)
    {
        if (!$key) {
            return redirect()->back();
        }
        return view('preview.index', ['key' => $key]);
    }
}
