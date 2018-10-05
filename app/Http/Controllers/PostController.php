<?php

namespace App\Http\Controllers;

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
        $post->time = microtime();

        $post->save();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $post,
        ], Response::HTTP_OK);


    }

    public function getAllPosts(){
        $post = posts::select('*')->get();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $post,
        ], Response::HTTP_OK);
    }
}
