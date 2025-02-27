<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($postId)
    {
        $post = Post::findOrFail($postId);
        $like = Like::where('user_id', Auth::id())->where('post_id', $postId)->first();

        if ($like) {
            $like->delete();
            return back()->with('success', 'Like retiré avec succès.');
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $postId
            ]);
            return back()->with('success', 'Post aimé avec succès.');
        }
    }
}