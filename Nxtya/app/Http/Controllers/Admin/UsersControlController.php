<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::all();
            if ($users->count() > 0) {
                return response()->json([
                    "data"=>$users
                 ]);
            }else{
                return response()->json([
                        "message"=> "no record found"
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
            "errors" =>"an exceptional error occured"
        ]);
        }  catch (\Error $e){
            return response()->json([
            "errors" =>$e-"an error occured"
        ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (User::destroy($id)){
                return response()->json([
                    "message" =>"record deleted"
                ]);
            }else{
                return response()->json([
                    "errors" =>"an error occured while deleting a record"
                ], 500);
            }
        }catch (\Exception $e){
            return response()->json([
            "errors" =>"an exceptional error occured"
        ], 500);
        }  catch (\Error $e){
            return response()->json([
            "errors" =>"an error occured"
        ], 500);
        }
    }
    public function forceDestroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return response()->json(['message' => 'Visitor successfully deleted']);
    }
    public function trashed()
    {
        $users = User::onlyTrashed()->restore();
        return response()->json(['data' => $users,
         'message' => 'Users retrieved successfully'], 200);
    }

}
