<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\JsonResponse
    */
    public function show($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
        return response()->json([
            "success" => false,
            "message" => "user not found."
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "user retrieved successfully.",
            "data" => $user
            ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAll(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'pseudo' => 'string|max:100',
        //     'page' => 'integer',
        //     'perPage' => 'integer'
        // ]);

        // $perPage = Input::get('perPage');

        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(), 400);
        // }

        // $perPage = $request->perpage;
        // dd($perPage);

        $users = User::all();
        return response()->json([
            'message'=> 'Ok',
            'data'=> $users    ], 200);
    }

    /**
    * Delete User
    * @return \Illuminate\Http\JsonResponse
    */
    function deleteUser($id){

        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "success" => false,
                "message" => "user not found."
                ], 404);
            }

        $user->delete();

        return response()->json([
            "success" => true,
            "message" => "user deleted successfully.",
            ], 201);
}
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\JsonResponse  $request
    * @param  int  $id
    * @return \Illuminate\Http\JsonResponse
    */
    function updateUser(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100|unique:users',
            'pseudo' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "success" => false,
                "message" => "user not found."
                ], 404);
            }

        $request->password = Hash::make($request->password);
        // dd($request->all());
        $user->update($request->all());

        return response()->json([
        "success" => true,
        "message" => "User updated successfully.",
        "data" => $user
        ], 200);
   }
}
