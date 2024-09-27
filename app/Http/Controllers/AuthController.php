<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255|unique:users',
            'phone_number'=>'required|string|max:255|unique:users',
            'password'=>'required|string|max:255',
        ]);

        $verificationCode = random_int(100000, 999999);

        $user=User::create([
            'name'=>$request->name,
            'phone_number'=>$request->phone_number,
            'password'=>Hash::make($request->password),
            'verification_code' => $verificationCode, 
        ]);


        $token=$user->createToken('auth_token')->plainTextToken;


        Log::info('verification code for user'.' ' .$user->name. ':' .$verificationCode);
        
        return response()->json(['user'=>$user,'token'=>$token]);

    }
    //542414    
    public function login(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'password'=>'required|string|max:255',
        ]);

        $user=User::where('name',$request->name)->first();


        if(!$user||!Hash::check($request->password,$user->password))
        {
            return response()->json(['message' => 'Invalid login details'], 401);

        }
        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Account not verified'], 403);
        }
        $token=$user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);

    }

    public function codeVerification(Request $request)
    {
        //this case if to need to verify after registration proccess with the provided token;

        //$request->validate(['code'=>'required|string|max:6']);
        //$user=Auth::user();


        $request->validate([
            'name'=>'required|string|exists:users',
            'code'=>'required|string|max:6']);

        $user=User::where('name','=',$request->name)->first();

        
        if($user->verification_code!=$request->code||!$request->code)
        {
             return response()->json(['message' => 'Verification denied']);
        }
       
            $user->email_verified_at=now();
            $user->verification_code=null;
            $user->save();

            return response()->json(['message' => 'Verification successful', 'user' => $user], 200);
        }
    
}
