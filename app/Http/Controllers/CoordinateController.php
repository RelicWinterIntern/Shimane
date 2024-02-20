<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinateController extends Controller
{
    //
    public function coordinate(Request $request)
    {
        dd($request->data);
        return response()->json(['success' => true]);
    }
}
