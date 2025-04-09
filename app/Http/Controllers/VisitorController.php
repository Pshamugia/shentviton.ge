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

        $v_hash = Str::random(32);

        $visitor_id = DB::table('visitors')->insertGetId([
            'v_hash' => $v_hash,
            'created_at' => now(),
        ]);

        if ($visitor_id) {
            Session::put('v_hash', $v_hash);
            Session::save();

            return response()->json([
                'status' => 'success',
                'v_hash' => $v_hash
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create visitor'
            ], 500);
        }
    }

    public function checkVisitor()
    {
        $v_hash = Session::get('v_hash');

        if ($v_hash) {
            $exists = DB::table('visitors')->where('v_hash', $v_hash)->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'success',
                    'v_hash' => $v_hash
                ], 200);
            }
        }

        return response()->json([
            'status' => 'not_found'
        ], 200);
    }
}
