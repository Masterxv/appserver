<?php

namespace App\Http\Controllers;

use App\Notification;
use App\SendPushNotification;
use App\Student;
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
        if ($likes != null) {
            $likes->type = 'like';
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


        $userLike = like::select('*')
            ->where('postId', '=', $request->postId)
            ->where('userId', '=', $request->userId)
            ->where('userType', '=', $request->userType)
            ->get()->first();


        if ($request->userType == 'ustad') {

            $user = Ustad::find($request->userId);
            $title = $user->name . " liked your post";
            $send = new SendPushNotification();
            $ustad = Ustad::find($post->userId);

            if($post->userId!=$user->id) {
                $send->sendNotification($ustad->firebaseid,
                    $title, $request->postId);
  $notificationold = Notification::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
             ->where('userType', '=', $request->userType)
            ->where('fromUserId', '=', $request->userId)
            ->get()->first();

                if($notificationold!=null){
               //  $notificationold->title = $title;
               //  $notificationold->fromUserId = $request->userId;
               // $notificationold->toUserId = $post->userId;
               // $notificationold->postId = $request->postId;
               //  $notificationold->userType = $request->userType;
               //     $notificationold->type = 'like';

               //  $notificationold->update();
                                                    $notificationold->delete();

                }
                // else{
                $notification = new Notification();
                $notification->title = $title;
                $notification->fromUserId = $request->userId;
               $notification->toUserId = $post->userId;
        $notification->time = round(microtime(true));

               $notification->type = 'like';
                $notification->postId = $request->postId;
        $notification->userType = $request->userType;

                $notification->save();
            // }

}
        } else if ($request->userType == 'student') {

            $user = student::find($request->userId);
            $title = $user->name . " liked your post";
            $send = new SendPushNotification();
            $ustad = Ustad::find($post->userId);

            // if($post->userId!=$user->id  ) {
                
                $send->sendNotification($ustad->firebaseid,
                    $title, $request->postId);

    $notificationold = Notification::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
             ->where('userType', '=', $request->userType)
            ->where('fromUserId', '=', $request->userId)
            ->get()->first();

                if($notificationold!=null){
               //  $notificationold->title = $title;
               //  $notificationold->fromUserId = $request->userId;
               // $notificationold->toUserId = $post->userId;
               // $notificationold->postId = $request->postId;
               //  $notificationold->userType = $request->userType;
               //                 $notificationold->type = 'like';

               //  $notificationold->update();
                                                    $notificationold->delete();

                }
                // else{
                $notification = new Notification();
                $notification->title = $title;
                               $notification->type = 'like';
        $notification->time = round(microtime(true));

                $notification->fromUserId = $request->userId;
                $notification->postId = $request->postId;
                               $notification->toUserId = $post->userId;

        $notification->userType = $request->userType;

                $notification->save();
            // }
        // }
        }


        $post->ustad = $ustad;

        $post->myLikeStatus = $userLike;
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
            $likes->type = 'unlike';
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

//        $post=Post::find($request->postId);
        $ustad = Ustad::find($post->userId);
        $post->ustad = $ustad;

        $post->myLikeStatus = $userLike;


        if ($request->userType == 'ustad') {

            $user = Ustad::find($request->userId);
            $title = $user->name . " unliked your post";
            $send = new SendPushNotification();
            $ustad = Ustad::find($post->userId);
            if($post->userId!=$user->id) {
                $send->sendNotification($ustad->firebaseid,
                $title, $request->postId);
            
            $notificationold = Notification::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
             ->where('userType', '=', $request->userType)
            ->where('fromUserId', '=', $request->userId)
            ->get()->first();

                if($notificationold!=null){
               //  $notificationold->title = $title;
               //  $notificationold->fromUserId = $request->userId;
               // $notificationold->toUserId = $post->userId;
               //                $notificationold->type = 'like';

               // $notificationold->postId = $request->postId;
               //  $notificationold->userType = $request->userType;
               //  $notificationold->update();
                                                $notificationold->delete();

                }
                // else{


                $notification = new Notification();
                $notification->title = $title;
                $notification->toUserId = $post->userId;
                $notification->fromUserId = $request->userId;
                $notification->postId = $request->postId;
                $notification->userType = $request->userType;
                               $notification->type = 'like';
        $notification->time = round(microtime(true));


                $notification->save();
            // }
        }

        } else if ($request->userType == 'student') {

            $user = student::find($request->userId);
            $title = $user->name . " unliked your post";
            $send = new SendPushNotification();
            $ustad = Ustad::find($post->userId);

            // if($post->userId!=$user->id) {
                $send->sendNotification($ustad->firebaseid,
                    $title, $request->postId);
            
            $notificationold = Notification::select('*')
            ->where('postId', '=', $request->postId)
            ->where('type', '=', 'like')
             ->where('userType', '=', $request->userType)
            ->where('fromUserId', '=', $request->userId)
            ->get()->first();

                if($notificationold!=null){
               //  $notificationold->title = $title;
               //  $notificationold->fromUserId = $request->userId;
               // $notificationold->toUserId = $post->userId;
               // $notificationold->postId = $request->postId;
               //  $notificationold->userType = $request->userType;
               // $notificationold->type = 'like';
               //  $notificationold->update();
                                                $notificationold->delete();

                }
                // else{

                $notification = new Notification();

                               $notification->type = 'like';
$notification->title = $title;
                $notification->fromUserId = $request->userId;
               $notification->toUserId = $post->userId;
        $notification->time = round(microtime(true));

                $notification->postId = $request->postId;
                $notification->userType = $request->userType;
                $notification->save();
            // }
        // }
        }


        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => 'unliked'],
            'posts' => $post,
            'myLikeStatus' => $userLike,

        ], Response::HTTP_OK);


    }
}
