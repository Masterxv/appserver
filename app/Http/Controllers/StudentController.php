<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash, Config;

use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return "abc";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $student=new Student();
//        $student->name=$request->name;
//        $student->email=$request->email;
//        $student->password=$request->password;
//        $student->username=$request->username;
//        $student->save();
//
//        return response()->json([
//            'error' => ['code'=>Response::HTTP_OK,'message'=>false],
//            'user' =>$student,
//        ], Response::HTTP_OK);
//

    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'username' => 'required|unique:students',
            'password' => 'required'
        ];
        $input = $request->only('name', 'email', 'password', 'username');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validator->messages()->first()],
                'user' => "",
            ], Response::HTTP_OK);

        }
        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = md5($request->password);
        $student->username = $request->username;
        $student->firebaseid = "";
        $student->logo = "";
        $student->active = "";
        $student->phone = "";
        $student->birthday = "";
        $student->address = "";
        $student->code = "";


        $student->save();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $student,
        ], Response::HTTP_OK);


    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $rules = [
            'email' => 'exists:students'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
                'user' => "",
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->where('password', '=', md5($request->password))
            ->get();
        if (is_null($student)) {
            return response()->json([
                'error' => ['code' => 302, 'message' => "Wrong password"],
                'user' => "",
            ], Response::HTTP_OK);
        }

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $student,
        ], Response::HTTP_OK);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function verifyCode(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'code' => $request->code
        ];

        $rules = [
            'email' => 'exists:students'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
                'user' => "",
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->where('code', '=', $request->code)
            ->get();
        if (is_null($student)) {
            return response()->json([
                'error' => ['code' => 302, 'message' => "Code error"],
                'user' => $student,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $student,
        ], Response::HTTP_OK);
    }

    public function newPassword(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $rules = [
            'email' => 'exists:students'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
                'user' => "",
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->get();
        $student->password = md5($request->password);
        $student->update();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $student,
        ], Response::HTTP_OK);
    }

    public function sendCode(Request $request)
    {
        $credentials = [
            'email' => $request->email,
        ];

        $rules = [
            'email' => 'exists:students'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => "Invalid email"],
                'user' => "",
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();

        $random = mt_rand(500000, 999999);

        $msg = "Please use this code to verify your account";
        $student->code = $random;

        $student->update(['code' => $random]);

//        mail('m.aliahmed0@gmail.com', 'Student App', $msg,'From: zaid.asif33@gmail.com');


        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "Code sent"],
            'user' => $student,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
