<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VisitorController extends Controller
{
    function createVisitor()
    {

        $visitor_id = DB::table('visitors')->insertGetId(
            [
                'v_hash' => Str::random(32),
                'created_at' => now(),
            ]
        );

        // dd($visitor_id);

        if ($visitor_id) {
            $v_hash = DB::table('visitors')->where('id', $visitor_id)->value('v_hash');
            Session::put('v_hash', $v_hash);
            return response()->json([
                'v_hash' => $v_hash
            ], 200);
        } else {
            return response()->json([
                'error' => 'Failed to create visitor'
            ], 500);
        }
    }
}
