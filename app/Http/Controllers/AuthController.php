<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
       try{
       $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        if($user){
            return ResponseHelper::success(message:'User has been registered successfully!', data:$user, statusCode: 201);
        }
        return ResponseHelper::error(message:'Unable to Register new user, Please try again', statusCode: 400);
       }catch(Exception $e){
        \Log::error('Unable to register User:'.$e->getMessage(). ' - Line no. '.$e->getLine());
        return ResponseHelper::error(message:'Unable to Register new user, Please try again', statusCode: 400);
       }
    }

    /**
     * Login A user.
     */
    public function login(LoginRequest $request)
    {
       try{
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]) ){
            return ResponseHelper::error(message:'Unable to Login due to invalid Credentials', statusCode: 400);
        }else{
            $user=Auth::user();
            // Create API Token
            $token=$user->createToken('My API Token')->plainTextToken;
            $authUser=[
                'user'=>$user,
                'token'=>$token
            ];
            return ResponseHelper::success(message:'You are logged in successfully!', data:$authUser, statusCode: 201);
        }

       }catch(Exception $e){
        \Log::error('Unable to register User:'.$e->getMessage(). ' - Line no. '.$e->getLine());
        return ResponseHelper::error(message:'Unable to Login, Please try again', statusCode: 500);
       }
    }

public function userProfile(){
try{
    $user=Auth::user();
    if($user){
        return ResponseHelper::success(message:'User Profile Fetch Successfully', data:$user, statusCode: 201);
    }else{
        return ResponseHelper::error(message:'Unable to Fetch User Profile due to invalid records', statusCode: 400);
    }
}catch(Exception $e){
    Log::error('Unable to Fetch user data:'.$e->getMessage(). ' - Line no. '.$e->getLine());
    return ResponseHelper::error(message:'Unable to Fetch User Data', statusCode: 500);
}
   }

   public function userLogout(){
    try{
        $user=Auth::user();
        if($user){
            $user->currentAccessToken()->delete();
            return ResponseHelper::success(message:'Logout Successfully', data:$user, statusCode: 201);
        }else{
            return ResponseHelper::error(message:'Unable to Logout due to invalid Token', statusCode: 400);
        }
    }catch(Exception $e){
        \Log::error('Unable to Logout due to invalid Token:'.$e->getMessage(). ' - Line no. '.$e->getLine());
        return ResponseHelper::error(message:'Unable to Logout due to invalid Token', statusCode: 500);
    }
   }
}
