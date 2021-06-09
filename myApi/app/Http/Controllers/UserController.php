<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

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
    public function showAll()
    {
        $users = User::all();
        return response()->json([
            'message'=> 'Ok',
            'data'=> $users    ], 200);
    }

    /**
    * Delete User
    * @return \Illuminate\Http\JsonResponse
    */
    function deleteUser(Request $request, $id){

        $user = User::find($id);
        $dataUser =User::find($id);

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
            "data" => $dataUser
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

        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "success" => false,
                "message" => "user not found."
                ], 404);
            }

        $user->update($request->all());

        return response()->json([
        "success" => true,
        "message" => "User updated successfully.",
        "data" => $user
        ], 200);
   }
}
