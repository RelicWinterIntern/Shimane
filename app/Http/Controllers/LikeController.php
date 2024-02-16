<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($post_id) {
        $like = new Like();
        $like->user_id = Auth::id();
        $like->post_id = $post_id;
        $like->save();

        return redirect()->route('post.index')->with('success', '投稿にいいねしました');
    }

    public function unlike($post_id) {
        Like::where('post_id',$post_id)->first()->delete();
        return redirect()->route('post.index')->with('success', 'いいねを取り消しました');
    }
}
