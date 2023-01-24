<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);



        return response()->json(['status' => 'success'], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
            "password" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "errors" => $validator->errors() -> first()
            ], 401);
        }

        if (!Auth::attempt($request->only(["email", "password"]))) {

            return response([
                "errors" => "Invalid Email or Password"
            ], 401);
        }
        $user = User::where("email", $request->email)->first();
        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            "user"=>$user,
            "token"=>$token,
        ], 200);

    }
    public function logout()
    {
        try{
            if (auth()->user()->tokens()->delete()) {
                return response()->json([
                    "status"=>"success",
                    "message"=>"User logged out successfully"

                ]);
            }else{
                return response()->json([
                    "status"=>"failed",
                    "errors"=>"an error occured while logging User out"
                ], 501);
            }

        } catch (\Exception $e) {
            return response()->json([
                "status"=>"failed",
                "errors"=>"an exceptional error occured"
            ], 501);
        } catch (\Error $e) {
            return response()->json([
                "status"=>"fails",
                "errors"=>"an error occured"
            ], 501);
        }
    }
}
