<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ustad;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Hash, Config, Image, File;
use Illuminate\Support\Facades\Storage;

class UstadController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:ustads',
            'username' => 'required|unique:ustads',
            'password' => 'required'
        ];
        $input = $request->only('name', 'email', 'password', 'username');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validator->messages()->first()],
            ], Response::HTTP_OK);

        }
        $ustadd = DB::table('students')->where('email', $request->email)->first();
        if ($ustadd != null) {

            return response()->json([
                'error' => ['code' => 302, 'message' => 'email already exist'],
            ], Response::HTTP_OK);
        }

        $ustadd = DB::table('students')->where('username', $request->email)->first();
        if ($ustadd != null) {

            return response()->json([
                'error' => ['code' => 302, 'message' => 'username already exist'],
            ], Response::HTTP_OK);
        }

        $ustad = new ustad();

        $ustad->name = $request->name;
        $ustad->email = $request->email;
        $ustad->password = md5($request->password);
        $ustad->username = $request->username;
        $ustad->active = "active";
        $ustad->phone = "";
        $ustad->firebaseid = $request->fcmKey;


        $ustad->save();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $ustad,
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
            'email' => 'exists:ustads'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
            ], Response::HTTP_OK);

        }


        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->where('password', '=', md5($request->password))
            ->get()->first();


        if ($ustad != null) {


            $ustad->firebaseid = $request->fcmKey;
            $ustad->update();

            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => false],
                'user' => $ustad,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => ['code' => 302, 'message' => "Wrong password"],
            ], Response::HTTP_OK);
        }

//        if ($ustad->count()) {
//
//                $ustad->first()->firebaseid=$request->fcmKey;
//                $ustad->update();
//
//            return response()->json([
//                'error' => ['code' => Response::HTTP_OK, 'message' => false],
//                'user' => $ustad->first(),
//            ], Response::HTTP_OK);
//
//
//        } else {
//
//            return response()->json([
//                'error' => ['code' => 302, 'message' => "Wrong password"],
//            ], Response::HTTP_OK);
//        }

    }


    public function changeStatus(Request $request)
    {

        $credentials = [
            'email' => $request->email,
        ];

        $rules = [
            'email' => 'exists:ustads'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
            ], Response::HTTP_OK);

        }

        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();
        $ustad->status = $request->status;
        $ustad->firebaseid = $request->firebaseid;
        $ustad->update();
        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $ustad,
        ], Response::HTTP_OK);

    }

    public function setustadstatus(Request $request)
    {

        $credentials = [
            'email' => $request->email,
        ];

        $rules = [
            'email' => 'exists:ustads'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
            ], Response::HTTP_OK);

        }

        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();
        $ustad->active = $request->status;
        $ustad->update();
        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'ustad' => $ustad,
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
            'email' => 'exists:ustads',
            'code' => 'required'

        ];

        $validation = Validator::make($request->only('email', 'code'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
            ], Response::HTTP_OK);

        }

        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->where('code', '=', $request->code)
            ->get()->first();

        if ($ustad != null) {

            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => false],
                'user' => $ustad->first(),
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
            'password' => $request->password,
            'code' => $request->code

        ];

        $rules = [
            'email' => 'exists:ustads'
        ];

        $validation = Validator::make($request->only('email'), $rules);
        if ($validation->fails()) {
            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
            ], Response::HTTP_OK);

        }

        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->where('code', '=', $request->code)
            ->get()->first();
        if ($ustad == null) {
            return response()->json([
                'error' => ['code' => 302, 'message' => "The Code and email does not match"],
            ], Response::HTTP_OK);

        } else {

            $ustad->password = md5($request->password);
            $ustad->update();

        }
        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => false],
            'user' => $ustad,
        ], Response::HTTP_OK);
    }


    public function editPassword(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'oldpassword' => $request->oldpassword
        ];

        $rules = [
            'email' => 'exists:ustads'
        ];

        $validation = Validator::make($request->only('email'), $rules);
        if ($validation->fails()) {
            return response()->json([
                'error' => ['code' => 302, 'message' => $validation->messages()->first()],
            ], Response::HTTP_OK);

        }

        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->where('password', '=', md5($request->oldpassword))
            ->get()->first();

        if ($ustad != null) {
            $ustad->password = md5($request->password);
            $ustad->update();

            return response()->json([

                'error' => ['code' => Response::HTTP_OK, 'message' => false],
                'user' => $ustad->first(),
            ], Response::HTTP_OK);

        } else {

            return response()->json([
                'error' => ['code' => 302, 'message' => "Wrong Old password"],
            ], Response::HTTP_OK);
        }


    }


    public function sendCode(Request $request)
    {
        $credentials = [
            'email' => $request->email,
        ];

        $rules = [
            'email' => 'exists:ustads'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => "Invalid email"],
            ], Response::HTTP_OK);

        }

        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();

        $random = mt_rand(500000, 999999);

        $msg = "Please use this code to verify your account";
        $ustad->code = $random;

        $ustad->update(['code' => $random]);


//        mail('m.aliahmed0@gmail.com', 'ustad App', $msg,'From: zaid.asif33@gmail.com');
        $data = [
            'data' => $random,

        ];
        ["data1" => $data];
        $email = $ustad->email;
        Mail::send('mail', ["data1" => $data], function ($message) use ($email) {
            $message->to($email)->subject("Password reset code");
            $message->from('info@ibadah.com', 'Ibadah');
        });

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "Code sent"],
            'user' => $ustad,
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
            'email' => 'exists:ustads'
        ];

        $rules = [
//            'name' => 'required',
            'email' => 'exists:ustads'
//            ,
//            'username' => 'required|exists:ustads',
//            'password' => 'required'
        ];


        $validation = Validator::make($request->only('email'), $rules);

        // if ($validation->fails()) {

        //     return response()->json([
        //         'error' => ['code' => 302, 'message' => "Invalid email"],
        //     ], Response::HTTP_OK);

        // }

        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();

        if (!is_null($request->newEmail)) {
            $ustad1 = ustad::select('*')
                ->where('email', '=', $request->newEmail)
                ->get()->first();

            if (is_null($ustad1)) {
                $ustad->email = $request->newEmail;
            } else {
                return response()->json([
                    'error' => ['code' => "302", 'message' => "Email already taken"],
                ], Response::HTTP_OK);
            }
        }
        if (!is_null($request->newUsername)) {
            $ustad2 = ustad::select('*')
                ->where('username', '=', $request->newUsername)
                ->get()->first();

            if (is_null($ustad2)) {
                $ustad->username = $request->newUsername;
            } else {
                return response()->json([
                    'error' => ['code' => "302", 'message' => "Username already taken"],
                ], Response::HTTP_OK);
            }

        }
        $ustad->password = $ustad->password;
        $ustad->name = $request->name;
        $ustad->phone = $request->phone;
        $ustad->price = $request->price;
        $ustad->skills = $request->skills;

        $ustad->info = $request->info;
        $ustad->category = $request->category;


        $ustad->update();


        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "Code sent"],
            'user' => $ustad,
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
            'email' => 'exists:ustads'
        ];

        $validation = Validator::make($request->only('email'), $rules);

        if ($validation->fails()) {

            return response()->json([
                'error' => ['code' => 302, 'message' => "Invalid email"],
            ], Response::HTTP_OK);

        }


        $ustad = ustad::select('*')
            ->where('email', '=', $request->email)
            ->get()->first();


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = md5(microtime());
            $ustads_path = public_path() . '/uploads/ustads/';
            $specified_customer_path = 'uploads/ustads/' . $ustad;
            if (!Storage::disk('public')->has($specified_customer_path . '/image')) {


            }

            $image->move($ustads_path, $name . ".png");

            $url = url('/') . '/uploads/ustads/' . basename($name . ".png");
            $ustad->logo = $url;
            $ustad->update();

        } else {
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "Image is null"],
            ], Response::HTTP_OK);
        }

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "Image successfully updated"],
            'user' => $ustad,
        ], Response::HTTP_OK);

    }

    public function logout(Request $request)
    {
        $ustad = Ustad::find($request->userId);
        if ($ustad == null) {
            return response()->json([
                'error' => ['code' => 302, 'message' => "No user found"],

            ], Response::HTTP_OK);
        } else {
            $ustad->firebaseid = "";
            $ustad->status = "offline";
            $ustad->update();
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "Logout"],

            ], Response::HTTP_OK);
        }

    }


    public function getUstad(Request $request)
    {
        $ustad = Ustad::find($request->ustadId);
        if ($ustad == null) {
            return response()->json([
                'error' => ['code' => 302, 'message' => "No user found"],

            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "ustad"],
                'ustad' => $ustad

            ], Response::HTTP_OK);
        }

    }

    public function getListOfUstad(Request $request)
    {
        $ustad = ustad::select('*')
            ->get();
        if ($ustad == null) {
            return response()->json([
                'error' => ['code' => 302, 'message' => "No ustad found"],

            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "ustad"],
                'ustad' => $ustad

            ], Response::HTTP_OK);
        }

    }
}