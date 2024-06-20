<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    Public function login(Request $request){
        try{
            $validation=Validator::make($request->all(),[
                "email" => 'required|email|exists:users',
                "password" => "string|required|min:6"
            ]);
            if($validation->fails()){
                return response()->json([
                  'sucsess'=>0,
                  'result'=>null,
                  'message'=>$validation->errors(),
                ],200);
            }
            if(!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'sucsess'=>0,
                    'result'=>null,
                    'message'=>'register first',
                  ],200);

            }
            $user=User::where('email',$request->email)->first();
            $token=$user->createToken("API-TOKEN")->plainTextToken;
            if($token){
                return response()->json([
                    'user'=>$user,
                    'token'=>$token
                  ],200);

            }
           else{
            return response()->json([
                'sucsess'=>0,
                'result'=>null,
                'message'=>'some thing went wrong',
              ],200);

           }

        }
        catch(Exception $e){
            return response()->json([
                'sucsess'=>0,
                'result'=>null,
                'message'=>$e,
              ],200);
        }

    }
    function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "email" => 'required|email|unique:users,email',
                'name' => 'required|string',
                "password" => "string|required|min:6"
            ]);

            if($validation->fails()){
                return response()->json([
                  'sucsess'=>0,
                  'result'=>null,
                  'message'=>$validation->errors(),
                ],200);
            }
            $user = User::create([
               'email'=>$request->email,
               'name'=>$request->name,
               'password'=>Hash::make($request->password),
            ]);

            $token=$user->createToken("API-TOKEN")->plainTextToken;
            if($token){
                return response()->json([
                    'user'=>$user,
                    'token'=>$token
                  ],200);

            }

          else{
            return response()->json([
                'sucsess'=>0,
                'result'=>null,
                'message'=>'some thing went wrong',
              ],200);
          }
        } catch (Exception $e) {
            return response()->json([
                'sucsess'=>0,
                'result'=>null,
                'message'=>$e
            ],200);
        }
    }

}
