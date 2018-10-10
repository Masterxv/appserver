<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ustad;

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
        $like->userType = $request->userType;


        $likes = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('userId', '=', $request->userId)
            ->where('userType', '=', $request->userType)
            ->get()->first();
        if ($likes !=null) {
            $likes->type='like';
            $likes->update();

        } else {
            $like->save();
        }

        $unlikes = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'unlike')
            ->count();

        $likescount = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
            ->count();

        $post = post::select('*')
            ->where('id', '=', $request->postId)
            ->get()->first();

        $post->likes = $likescount;
        $post->unlikes = $unlikes;

        // $userLike = like::select('*')
        //     ->where('postId', '=', $request->postId)
        //     ->where('userId', '=', $request->userId)
        //     ->get()->first();


        $userLike = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('userId', '=', $request->userId)
            ->where('userType', '=', $request->userType)
            ->get()->first();


      $ustad = Ustad::find($request->userId);
        $post->ustad = $ustad;

        $post->myLikeStatus= $userLike;
        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => 'liked'],
            'posts' => $post,
            'myLikeStatus' => $userLike,

        ], Response::HTTP_OK);

    }

    public function unlikePost(Request $request)
    {
        $like = new Like();
        $like->postId = $request->postId;
        $like->userId = $request->userId;
        $like->type = 'unlike';
        $like->userType = $request->userType;


        $likes = like::select('*')
            ->where('postId', '=', $request->postId)
                        ->where('userType', '=', $request->userType)

            ->where('userId', '=', $request->userId)
            ->get()->first();

        if ($likes != null) {
            $likes->type='unlike';
            $likes->update();
        } else {
            $like->save();
        }

        $unlikes = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'unlike')
            ->count();
        $post = post::select('*')
            ->where('id', '=', $request->postId)
            ->get()->first();

        $likescount = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
            ->count();
        $post->likes = $likescount;
        $post->unlikes = $unlikes;


        $userLike = like::select('*')
            ->where('postId', '=', $request->postId)
                        ->where('userType', '=', $request->userType)

            ->where('userId', '=', $request->userId)
            ->get()->first();

  $ustad = Ustad::find($request->userId);
        $post->ustad = $ustad;

        $post->myLikeStatus= $userLike;

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => 'unliked'],
            'posts' => $post,
            'myLikeStatus' => $userLike,

        ], Response::HTTP_OK);


    }
}
