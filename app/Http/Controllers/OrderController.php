<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\order;
use Illuminate\Support\Facades\Validator;
use Hash, Config, Image, File;
use Illuminate\Support\Facades\Storage;
use App\Ustad;
use App\Student;



class OrderController extends Controller
{

    public function makeOrder(Request $request)
    {
        $order = new order();
        $order->starttime = $request->starttime;
        $order->date = $request->date;
        $order->endtime = $request->endtime;
        $order->totaltime = $request->totaltime;
        $order->totalbalance = $request->totalbalance;
        $order->ustadId = $request->ustadId;
        $order->status = $request->status;
        $order->service = $request->service;
        $order->studentId = $request->studentId;

        $order->save();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'order' => $order,
        ], Response::HTTP_OK);


    }

 public function getAllOrdersOfStudent(Request $request)
    {
        $studentId=$request->studentId;
        $order = order::select('*')
         ->where('studentId', '=', $studentId)
        ->get();

        foreach ($order as $value) {
            $ustad = Ustad::find($value->ustadId);
            $value->ustad = $ustad;
            $student = Student::find($value->studentId);
            $value->student = $student;


        }

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'orders' => $order,
        ], Response::HTTP_OK);
    }


}
