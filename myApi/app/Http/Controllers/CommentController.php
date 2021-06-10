<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $comment = new Comment;
        $comment->body = $request->body;
        $comment->user_id = $request->user_id;
        $comment->video_id = $id;
        $comment->save();

        return response()->json([
            'message' => 'comment successfully registered',
            'data' => $comment
        ], 201);
    }

    public function showVideoComments(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string|between:1,250',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $comment = Comment::create($validator->validated());

        return response()->json([
            'message' => 'User successfully registered',
            'data' => $comment
        ], 201);
    }
}
