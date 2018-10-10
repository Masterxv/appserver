<?php

namespace App\Http\Controllers;

use App\User;
use App\Ustad;
use App\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;
use Illuminate\Support\Facades\Validator;
use Hash, Config, Image, File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function makePost(Request $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->text = $request->text;
        $post->type = $request->type;
        $post->category = $request->category;
        $post->userId = $request->userId;
        $post->time = round(microtime(true));

        $post->save();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $post,
        ], Response::HTTP_OK);


    }

    public function getAllPostsUstad(Request $request)
    {
        $userId=$request->userId;
        $ustadId=$request->ustadId;
        $post = post::select('*')
         ->where('userId', '=', $ustadId)
        ->get();

        foreach ($post as $value) {
            $ustad = Ustad::find($value->userId);
            $value->ustad = $ustad;
            $likes = like::select('*')
                ->where('postId', '=', $value->id)
                ->where('type', '=', 'like')
                ->count();

            $unlikes = like::select('*')
                ->where('postId', '=', $value->id)
                ->where('type', '=', 'unlike')
                ->count();
            $value->unlikes=$unlikes;
            $value->likes=$likes;

            $userLike = like::select('*')
                ->where('postId', '=', $value->id)
                ->where('userId', '=', $userId)
                 ->where('userType', '=', $request->userType)

                ->get()->first();

            $value->myLikeStatus=$userLike;

        }

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'posts' => $post,
        ], Response::HTTP_OK);
    }

     public function getAllPosts(Request $request)
    {
        $userId=$request->userId;
        $post = post::select('*')->get();

        foreach ($post as $value) {
            $ustad = Ustad::find($value->userId);
            $value->ustad = $ustad;
            $likes = like::select('*')
                ->where('postId', '=', $value->id)
                ->where('type', '=', 'like')
                ->count();

            $unlikes = like::select('*')
                ->where('postId', '=', $value->id)
                ->where('type', '=', 'unlike')
                ->count();
            $value->unlikes=$unlikes;
            $value->likes=$likes;

            $userLike = like::select('*')
                ->where('postId', '=', $value->id)
                ->where('userId', '=', $userId)
                 ->where('userType', '=', $request->userType)

                ->get()->first();

            $value->myLikeStatus=$userLike;

        }

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'posts' => $post,
        ], Response::HTTP_OK);
    }
}
