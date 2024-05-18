<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function registration(Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email|min:10|max:20|unique:users',
                'password' => 'required|min:5|max:10',
            ]);
            $firstName = $request->firstName;
            $lastName = $request->lastName;
            $email = $request->email;
            $password = $request->password;
            User::create([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password,
            ]);

            // return redirect()->route('login');
            return response()->json([
                'status' => 'success',
                'msg' => 'Registration successs'
            ]);
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:5|max:10'
            ]);
            $email = $request->email;
            $password = $request->password;
            $user = User::where('email', $email)->where('password', $password)->first();
            // return 'login success';
            // dd($user);
            if ($user) {
                $token = JWTToken::createToken($user->email, $user->id);
                return response()->json([
                    'msg' => 'Login successs',
                    'token' => $token
                ]);
            } else {
                return 'email or pass rong';
            }
        } catch (Exception $logerror) {
            return $logerror->getMessage();
        }
    }

    public function sendOtp(Request $request)
    {
        try {
            $email = $request->email;
            $otp = rand(1000, 9999);

            $user = User::where('email', $email)->first();

            if ($user) {
                Mail::to($email)->send(new OTPMail($otp));
                User::where('email', $email)->update(['otp' => $otp]);

                return response()->json([
                    'status' => 'success',
                    'msg' => 'OTP has been sent in your mail'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }


    public function verifyOtp(Request $request)
    {
        try {
            $email = $request->email;
            $otp = $request->otp;

            $user = User::where('email', $email)->where('otp', $otp)->first();
            if ($user) {
                User::where('email', $email)->where('otp', $otp)->update(['otp' => '0']);
                return response()->json([
                    'status' => 'success',
                    'msg' => 'OTP has been verifyed successfully'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }
}
