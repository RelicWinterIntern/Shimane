<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinateController extends Controller
{
    //
    public function coordinate(Request $request)
    {
        $positionData = $request->input();
        echo response()->json(['success' => true, 'data' => $positionData["coords"]]);
    }
}
