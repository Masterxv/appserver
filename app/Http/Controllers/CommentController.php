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

        $post = Post::find($request->postId);
        if ($request->userType == 'ustad') {

            $user = Ustad::find($request->userId);
            $title = $user->name . " commented on your post";
            $send = new SendPushNotification();
            $ustad = Ustad::find($post->userId);

            if($post->userId!=$user->id) {
                $send->sendNotification($ustad->firebaseid,
                    $title, $request->postId);

  $notificationold = Notification::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'comment')
             ->where('userType', '=', $request->userType)
            ->where('fromUserId', '=', $request->userId)
            ->get()->first();

                            if($notificationold!=null){
                            	$notificationold->delete();
        //         $notificationold->title = $title;
        //         $notificationold->fromUserId = $request->userId;
        //         $notificationold->postId = $request->postId;
        // $notificationold->userType = $request->userType;
        //        $notificationold->toUserId = $post->userId;
        //                        $notificationold->type = 'comment';

        //         $notificationold->update();
            
        }
        // else{
    $notification = new Notification();
                $notification->title = $title;
                $notification->fromUserId = $request->userId;
                $notification->postId = $request->postId;
        $notification->userType = $request->userType;
               $notification->toUserId = $post->userId;
                               $notification->type = 'comment';
        $notification->time = round(microtime(true));

                $notification->save();
            
        // }

        }
        } else if ($request->userType == 'student') {

            $user = Ustad::find($request->userId);
            $title = $user->name . " commented on your post";
            $send = new SendPushNotification();
            $ustad = Ustad::find($post->userId);

            if($post->userId!=$user->id) {
                $send->sendNotification($ustad->firebaseid,
                    $title, $request->postId);

 		 $notificationold = Notification::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'comment')
             ->where('userType', '=', $request->userType)
            ->where('fromUserId', '=', $request->userId)
            ->get()->first();

                            if($notificationold!=null){
                            	$notificationold->delete();
          //       $notificationold = new Notification();
          //       $notificationold->title = $title;
          //       $notificationold->fromUserId = $request->userId;
          //       $notificationold->postId = $request->postId;
		        // $notificationold->userType = $request->userType;
          //      $notificationold->toUserId = $post->userId;
          //       $notificationold->type = 'comment';

          //       $notificationold->update();
            
        }
        // else{

                $notification = new Notification();
                $notification->title = $title;
                $notification->fromUserId = $request->userId;
                $notification->postId = $request->postId;
      		   $notification->userType = $request->userType;
               $notification->toUserId = $post->userId;
                $notification->type = 'comment';
        $notification->time = round(microtime(true));

                $notification->save();
            // }
        
       }
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
