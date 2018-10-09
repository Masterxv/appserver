<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Like;
use App\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Hash, Config, Image, File;
use Illuminate\Support\Facades\Storage;


class LikeController extends Controller
{


    //
    public function likePost(Request $request)
    {
        $like = new Like();
        $like->postId = $request->postId;
        $like->userId = $request->userId;
        $like->type = 'like';

        $like->save();

        $likes = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
            ->count();

        $unlikes = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'unlike')
            ->count();

        $post = post::select('*')
            ->where('id', '=', $request->postId)
            ->get()->first();

        $post->likes = $likes;
        $post->unlikes = $unlikes;

        $userLike = like::select('*')
            ->where('postId', '=', $request->postid)
            ->where('userId', '=', $request->userId)
            ->get()->first();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => 'liked'],
            'post' => $post,
            'liked'=>$userLike,

        ], Response::HTTP_OK);

    }

    public function unlikePost(Request $request)
    {
        $like = new Like();
        $like->postId = $request->postId;
        $like->userId = $request->userId;
        $like->type = 'unlike';

        $like->save();

        $likes = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
            ->count();

        $unlikes = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'unlike')
            ->count();

        $post = post::select('*')
            ->where('id', '=', $request->postId)
            ->get()->first();

        $post->likes = $likes;
        $post->unlikes = $unlikes;

        $userLike = like::select('*')
            ->where('postId', '=', $request->postid)
            ->where('userId', '=', $request->userId)
            ->get()->first();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => 'unliked'],
            'likes' => $likes,
            'unlikes' => $unlikes,
            'myLikes' => $userLike,
        ], Response::HTTP_OK);

    }
}
