<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
    // public function create()
    // {
    //     $like = new Like();
    //     $like->user_id = Auth::id();
    //     $like->post_id = Auth::id();
    //     $like->save();

    //     return redirect()->route('post.index')->with('success', '投稿にいいねされました');
    // }

    // public function show($id)
    // {
    //     $post = Post::findOrFail($id);
    //     return view('post.show', compact('post'));
    // }
}
