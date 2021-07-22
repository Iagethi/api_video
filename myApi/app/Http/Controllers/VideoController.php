<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function videoUpload(Request $req, $id)
    {

        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max: 100',
            'source' => 'required|mimes:avi,mkv,mp4,m4v,mov,qt,flv,asf'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "success" => true,
                "message" => "User not found.",
            ], 404);
        }

        $fileModel = new Video;

        if ($req->file()) {
            $fileName = time() . '_' . $req->source->getClientOriginalName();
            $filePath = $req->file('source')->storeAs('uploads', $fileName, 'public');

            $fileModel->name = $req->name;
            $fileModel->source = 'app/public/' . $filePath;
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
        ], 400);
    }

    public function showVideoOfUser($id)
    {

        $videos = Video::where('user_id', $id)->get();

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
            "message" => "video retrieved successfully.",
            "data" => $videos
        ], 200);
    }

    /**
     * Show Videos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllVideo(Request $request)
    {
        $videos = DB::table('videos');
        if ($request->has("name"))
            $videos = $videos->where("name", $request->get("name"));
        if ($request->has("user"))
            $videos = $videos->where("user", $request->get("user"));
        if ($request->has("duration"))
            $videos = $videos->where("duration", $request->get("duration"));

        $pagination = $videos->Paginate($request->has("perPage") ? $request->get("perPage") : 5);

        // dd($request->all());
        if ($request->has("page"))
            if ($request->get("page") == 0 || $request->get("page") > $pagination->lastPage())
                return response()->json(null, 400);
        // dd($request->page);
        $toRender = [];
        foreach ($pagination->items() as $vid) {
            array_push($toRender, (Video::find($vid->id)));
        }

        return response()->json([
            'message' => 'OK',
            'data' => $toRender,
            "pager" => collect([
                "current" => $pagination->currentPage(),
                "total" => $pagination->lastPage()
            ])
        ], 200);

        // $videos = Video::all();
        // return response()->json([
        // 'message'=> 'Ok',
        // 'data'=> $videos    ], 200);
    }

    public function updateVideo(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max: 100',
        ]);

        if ($validator->fails()) {
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

    public function deleteVideo($id)
    {

        $video = Video::find($id);
        $path = str_replace('/', '\\', $video->source);

        // dd($video);
        if (is_null($video)) {
            return response()->json([
                "success" => false,
                "message" => "video not found."
            ], 404);
        }

        if (is_file($path)) {

            unlink(storage_path($path));
            $video->delete();
            return response()->json([
                "success" => true,
                "message" => "video deleted successfully.",
            ], 204);
        } else {
            return response()->json([
                "success" => false,
                "message" => "file do not exist.",
            ], 404);
        }
    }
}
