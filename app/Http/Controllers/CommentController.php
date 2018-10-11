<?php

namespace App\Http\Controllers;
use App\Notification;
use App\Post;
use App\SendPushNotification;
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

        $post=Post::find($request->postId);
        if ($request->userType == 'ustad') {
            $ustad = Ustad::find($post->userId);
            $title = $ustad->name . " commented on your post";
            $ustad = Ustad::find($request->userId);
            $send = new SendPushNotification();
            $send->sendNotification($ustad->firebaseid,
                $title);

            $notification = new Notification();
            $notification->title = $title;
            $notification->fromUserId = $request->userId;
            $notification->toUserId = $post->userId;
            $notification->postId = $request->postId;

            $notification->save();


        } else if ($request->userType == 'student') {
            $ustad = Ustad::find($post->userId);
            $student = Student::find($request->userId);
            $title = $student->name . " commented on your post";
            $send = new SendPushNotification();
            $send->sendNotification($ustad->firebaseid,
                $title);

            $notification = new Notification();
            $notification->title = $title;
            $notification->fromUserId = $request->userId;
            $notification->toUserId = $post->userId;
            $notification->postId = $request->postId;

            $notification->save();
        }

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
