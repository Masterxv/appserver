<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Notification;

class NotificationsController extends Controller
{
		
	public function getAllNotification(Request $request)
    {
        $notification = Notification::select('*')
            ->where('toUserId', '=', $request->userId)
               ->where('userType', '=', $request->userType)
            ->get();
        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'comments' => $notification,
        ], Response::HTTP_OK);
    }
}
