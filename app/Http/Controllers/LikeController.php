<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Request $request) {
        $validatedData = $request->validate([
            'post_id' => 'required',
        ]);

        $like = new Like();
        $like->user_id = Auth::id();
        $like->post_id = $validatedData['post_id'];
        $like->save();

        return redirect()->route('post.index')->with('success', '投稿にいいねしました');
    }
}
