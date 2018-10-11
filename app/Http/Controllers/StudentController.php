<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash, Config, Image, File;
use Illuminate\Support\Facades\Storage;


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
        $student = new Student();
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
            ], Response::HTTP_OK);

        }
        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = md5($request->password);
        $student->username = $request->username;
        $student->logo = "";
        $student->active = "";
        $student->phone = "";
        $student->birthday = "";
        $student->address = "";
        $student->code = "";
        $student->firebaseid=$request->fcmKey;

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
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->where('password', '=', md5($request->password))
            ->get()->first();

        if ($student->count()) {

            $student->firebaseid=$request->fcmKey;
            $student->save();

            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => false],
                'user' => $student->first(),
            ], Response::HTTP_OK);

        } else {

            return response()->json([
                'error' => ['code' => 302, 'message' => "Wrong password"],
            ], Response::HTTP_OK);
        }

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
            'email' => 'exists:students',
            'code' => 'required'

        ];

        $validation = Validator::make($request->only('email', 'code'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->where('code', '=', $request->code)
            ->get()->first();

        if ($student->count()) {

            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => false],
                'user' => $student->first(),
            ], Response::HTTP_OK);

        } else {
            return response()->json([
                'error' => ['code' => 302, 'message' => "Code is Wrong"],
            ], Response::HTTP_OK);
        }
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
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();
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
    public function editProfile(Request $request)
    {
        $credentials = [
            'email' => $request->email,
        ];


        $rules = [
            'email' => 'exists:students'
        ];

        $rules = [
//            'name' => 'required',
            'email' => 'exists:students'
//            ,
//            'username' => 'required|exists:students',
//            'password' => 'required'
        ];


        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => "Invalid email"],
            ], Response::HTTP_OK);

        }

        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();

        if (!is_null($request->newEmail)) {
            $student1 = student::select('*')
                ->where('email', '=', $request->newEmail)
                ->get()->first();

            if (is_null($student1)) {
                $student->email = $request->newEmail;
            } else {
                return response()->json([
                    'error' => ['code' => "302", 'message' => "Email already taken"],
                ], Response::HTTP_OK);
            }
        }
        if (!is_null($request->newUsername)) {
            $student2 = student::select('*')
                ->where('username', '=', $request->newUsername)
                ->get()->first();

            if (is_null($student2)) {
                $student->username = $request->newUsername;
            } else {
                return response()->json([
                    'error' => ['code' => "302", 'message' => "Username already taken"],
                ], Response::HTTP_OK);
            }

        }
        $student->password = $student->password;
        $student->phone = $request->phone;
        $student->birthday = $request->birthday;
        $student->address = $request->address;

        $student->name = $request->name;

        $student->update();


        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "Code sent"],
            'user' => $student,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
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
            ], Response::HTTP_OK);

        }


        $student = student::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = md5(microtime());
            $customers_path = public_path() . '/uploads/customers/';
            $specified_customer_path = 'uploads/customers/' . $student;
            if (!Storage::disk('public')->has($specified_customer_path . '/image')) {


            }

            $image->move($customers_path, $name . ".png");

            $url = url('/') . '/uploads/customers/' . basename($name . ".png");
            $student->logo = $url;
            $student->update();

        } else {
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "Image is null"],
            ], Response::HTTP_OK);
        }

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "Image successfully updated"],
            'user' => $student,
        ], Response::HTTP_OK);

    }


}
