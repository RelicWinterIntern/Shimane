<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class PostController extends Controller
{
    public function index(Request $request)
    {   
        if (session()->has('posts')) {
            $posts = session('posts');
            $isNear = true;
            return view('post.index', compact('posts', 'isNear'));
        } else {
            $posts = Post::orderBy('updated_at', 'desc')->get();
            $isNear = false;
            return view('post.index', compact('posts', 'isNear'));
        }
    }

    // https://qiita.com/takedomin/items/12e206d2a2ba285cee7c
    public function near(Request $request)
    {   
        $validatedData = $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        $latitude = $validatedData['latitude'];
        $longitude = $validatedData['longitude'];
        $posts = Post::select('*', 
        DB::raw('6370 * ACOS(COS(RADIANS('.$latitude.')) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS('.$longitude.')) 
                + SIN(RADIANS('.$latitude.')) * SIN(RADIANS(latitude))) as distance'))
                ->whereRaw('6370 * ACOS(COS(RADIANS('.$latitude.')) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS('.$longitude.')) 
                + SIN(RADIANS('.$latitude.')) * SIN(RADIANS(latitude))) < 50')
                ->orderBy('updated_at', 'desc')
                ->get();
        $isNear = true;
        return redirect()->route('post.index')->with('posts', $posts);
    }

    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $post = new Post();
        $post->title = $validatedData['title'];
        $post->body = $validatedData['body'];

        if ($request->hasFile('image')) {
            // フォームでリクエストされた画像を取得
            $img = $request->file('image');

            // アップロードされたファイル名を取得
            $file_name = $request->file('image')->getClientOriginalName();

            // storage > public > img 配下に画像が一時的に保存される
            $dir = 'img';
            $img->storeAs('/' . $dir, $file_name);
            $imgPath = '/' . $dir . '/' . $file_name;

            // store処理が実行できたらDBに保存処理を実行
            if ($imgPath) {
                // DBにPathを登録する処理
                $post->image = $imgPath;
            }
        }
        $post->user_id = Auth::id();
        $post->latitude = $validatedData['latitude'];
        $post->longitude = $validatedData['longitude'];
        $post->save();

        return redirect()->route('post.index')->with('success', '投稿が作成されました');
    }

    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->get();
        return view('my-posts', compact('posts'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('post.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $validatedData['title'];
        $post->body = $validatedData['body'];
        $post->save();

        return redirect()->route('myposts')->with('success', '投稿が更新されました');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('myposts')->with('success', '投稿が削除されました');
    }

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
    }

    public function like($id)
    {
        Like::create([
        'post_id' => $id,
        'user_id' => Auth::id(),
        ]);

        session()->flash('success', '投稿にいいねされました');

        return redirect()->back();
    }


    public function unlike($id)
    {
        $like = Like::where('post_id', $id)->where('user_id', Auth::id())->first();
        $like->delete();

        session()->flash('success', '投稿にいいねが消されました');

        return redirect()->back();
    }
}