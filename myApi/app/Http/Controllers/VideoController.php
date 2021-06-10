<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function videoUpload(Request $req, $id){
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max: 100',
            'source' => 'required|mimes:avi,mkv,mp4,m4v,mov,qt,flv,asf'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $fileModel = new Video;

        if($req->file()) {
            $fileName = time().'_'.$req->source->getClientOriginalName();
            $filePath = $req->file('source')->storeAs('uploads', $fileName, 'public');

            $fileModel->name = $req->name;
            $fileModel->source = '/storage/' . $filePath;
            $fileModel->user_id = $id;
            $fileModel->save();

            return response()->json([
                "success" => true,
                "message" => "Video has been uploaded.",
                "data" => $fileModel
                ], 201);

        }

        return response()->json([
            "success" => false,
            "message" => "Video not uploaded.",
            ], 201);

   }

   public function showVideoOfUser($id) {

        $videos = Video::where('user_id',$id)->get();

        foreach ($videos as $video) {
            $video->name;
        }

        if (count($videos) <= 0) {
        return response()->json([
            "success" => false,
            "message" => "video not found."
            ], 404);
        }
        return response()->json([
            "success" => true,
            "message" => "user retrieved successfully.",
            "data" => $videos
            ], 200);
   }

   public function showAllVideo() {

        $videos = Video::all();
        return response()->json([
        'message'=> 'Ok',
        'data'=> $videos    ], 200);
   }

   public function updateVideo(Request $request, $id)
   {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max: 100',
    ]);

    if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
    }

       $video = Video::find($id);
       $video->name = $request->name;
        // dd($video);
       $video->update();

       return response()->json([
        "success" => true,
        "message" => "user retrieved successfully.",
        "data" => $video
        ], 200);
   }

   public function deleteVideo($id) {

    $video = Video::find($id);
    $dataVideo =Video::find($id);

    // dd($video);
    if (is_null($video)) {
        return response()->json([
            "success" => false,
            "message" => "video not found."
            ], 404);
        }

    unlink(storage_path('app\\public\\uploads\\'.$video->source));
    $video->delete();

    return response()->json([
        "success" => true,
        "message" => "video deleted successfully.",
        "data" => $dataVideo
        ], 204);
}
}
