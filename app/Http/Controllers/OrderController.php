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
    	$callStart=$request->starttime;
    	  $callEnd=$request->endtime;

        $ustadId=$request->ustadId;
    	    $orders = order::select('*')
         ->where('ustadId', '=', $ustadId)
          ->where('status', '=', 'Accepted')
        ->get();

        	 $flag=false;
        	if($orders->count()>0){
        		return $orders->count();
        	foreach ($orders as $order ) {
        		$startDB=$order->starttime;

        		$endDB=$order->endtime;

        		if($callStart<$endDB && $callEnd>$startDB){
        			$flag=true;
        		
        		}

			}
			if($flag){
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

        // return response()->json([
        //     'error' => ['code' => Response::HTTP_OK, 'message' => false],
        //     'order' => $order,
        // ], Response::HTTP_OK);



        		}else{
        // 			  return response()->json([
        //     'error' => ['code' => Response::HTTP_OK, 'message' => "Please select other time"],
        // ], Response::HTTP_OK);

        		}



       		
        }else{
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
        		return $order;

       //  return response()->json([
       //      'error' => ['code' => Response::HTTP_OK, 'message' => false],
       //      'order' => $order,
       //  ], Response::HTTP_OK);
      	}
      
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

    public function getAllOrdersOfUstad(Request $request)
    {
        $studentId=$request->ustadId;
        $order = order::select('*')
         ->where('ustadId', '=', $studentId)
         ->where('status', '!=', 'Completed')

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


       public function getAllCompleteOrdersOfUstad(Request $request)
    {
        $studentId=$request->studentId;
        $order = order::select('*')
         ->where('ustadId', '=', $studentId)
         ->where('status', '=', 'Completed')

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
public function getOrder(Request $request)
    {
        $id=$request->id;
        $order = order::select('*')
         ->where('id', '=', $id)
			->get()->first();
		if ($order == null) {
			return response()->json([
				'error' => ['code' => 302, 'message' => "The Order does not exist"],
			], Response::HTTP_OK);

		} else {
            $ustad = Ustad::find($order->ustadId);
            $order->ustad = $ustad;
            $student = Student::find($order->studentId);
            $order->student = $student;
		return response()->json([
			'error' => ['code' => Response::HTTP_OK, 'message' => false],
			'order' => $order,
		], Response::HTTP_OK);

	}

 }
public function setOrderStatus(Request $request)
    {
        $id=$request->id;
        $order = order::select('*')
         ->where('id', '=', $id)
			->get()->first();
		if ($order == null) {
			return response()->json([
				'error' => ['code' => 302, 'message' => "The Order does not exist"],
			], Response::HTTP_OK);

		} else {
			$order->status=$request->status;
			$order->update();
            $ustad = Ustad::find($order->ustadId);
            $order->ustad = $ustad;
            $student = Student::find($order->studentId);
            $order->student = $student;
		return response()->json([
			'error' => ['code' => Response::HTTP_OK, 'message' => false],
			'order' => $order,
		], Response::HTTP_OK);

	}

 }



public function acceptOrder(Request $request)
    {
        $id=$request->id;
        $order = order::select('*')
         ->where('id', '=', $id)
			->get()->first();
		if ($order == null) {
			return response()->json([
				'error' => ['code' => 302, 'message' => "The Order does not exist"],
			], Response::HTTP_OK);

		} else {
			$order->status='Completed';
            $ustad = Ustad::find($order->ustadId);
            $order->ustad = $ustad;
            $student = Student::find($order->studentId);
            $order->student = $student;
		return response()->json([
			'error' => ['code' => Response::HTTP_OK, 'message' => false],
			'order' => $order,
		], Response::HTTP_OK);

	}
}


public function cancelOrder(Request $request)
    {
        $id=$request->id;
        $order = order::select('*')
         ->where('id', '=', $id)
		->get()->first();
		if ($order == null) {
			return response()->json([
				'error' => ['code' => 302, 'message' => "The Order does not exist"],
			], Response::HTTP_OK);

		} else {
			$order->status='Cancelled';
            $ustad = Ustad::find($order->ustadId);
            $order->ustad = $ustad;
            $student = Student::find($order->studentId);
            $order->student = $student;
		return response()->json([
			'error' => ['code' => Response::HTTP_OK, 'message' => false],
			'order' => $order,
		], Response::HTTP_OK);

	}

 }



}
