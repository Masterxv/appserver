<?php

namespace App\Http\Controllers;
use App\Ustad;

use Illuminate\Http\Request;
use App\Comments;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Hash, Config, Image, File;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    //
    public function postComment(Request $request)
    {

        $comment = new Comments();
        $comment->postId = $request->postId;
        $comment->userId = $request->userId;
        $comment->time = $milliseconds = round(microtime(true));
        $comment->text = $request->text;
        $comment->userType = $request->userType;
        $comment->save();


        $comments = comments::select('*')
            ->where('postId', '=', $request->postId)
            ->get();

                    foreach ($comments as $value) {
            $ustad = Ustad::find($value->userId);
            $value->ustad = $ustad;

        }


        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'comments' => $comments,
        ], Response::HTTP_OK);
    }

    public function getPostComments(Request $request)
    {
        $comments = comments::select('*')
            ->where('postId', '=', $request->postId)
            ->get();
    foreach ($comments as $value) {
            $ustad = Ustad::find($value->userId);
            $value->ustad = $ustad;
        }
        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'comments' => $comments,
        ], Response::HTTP_OK);
    }
}
