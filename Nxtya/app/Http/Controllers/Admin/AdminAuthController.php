<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
            "password" => ["required", "string"]
        ]);

        if ($validator->fails()){
            return response()->json(["errors" => $validator->errors()], 401);
        }

        if (!Auth::guard("admin")->attempt($request->only(["email", "password"])))
        {
            return response(["errors" => "Invalid Email or Password"], 401);
        }
        $admin = Admin::where("email", $request->email)->first();
        $token = $admin->createToken('token-name')->plainTextToken;

        return response()->json([
            "admin"=>$admin,
            "token"=>$token,
        ], 200);

    }
    public function logout()
    {
        try{
            if (auth()->user()->tokens()->delete()) {
                return response()->json([
                    "status"=>"success",
                    "message"=>"Admin logged out successfully"

                ]);
            }else{
                return response()->json([
                    "status"=>"failed",
                    "errors"=>"an error occured while logging admin out"
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
