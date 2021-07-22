<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\VideoFormat;

class VideoFormatController extends Controller
{
    /**
     * Encode video by id
     *
     * @param string $url
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function encode(Video $id, Request $request)
    {
        $supported_format = ['1080', '720', '480', '360', '240', '144'];
        $supported_mimes = ['video/avi', 'video/mpeg', 'video/quicktime', 'video/mp4'];

        $file_info = new \finfo(FILEINFO_MIME_TYPE);
        $mime_type =  $file_info->buffer(file_get_contents('../storage/' . $request->get('file')));

        if (!in_array($request->get('format'), $supported_format) || !in_array($mime_type, $supported_mimes)) {
            return response()->json([
                'message' => 'Video format not supported',
                'code' => 10001,
            ], 400);
        }

        // dd($id->addMedia('../storage/' . $request->get('file'))->withCustomProperties(['format' => $request->get('format')])->toMediaCollection('videos'));
        try {
            $id->addMedia('../storage/' . $request->get('file'))->withCustomProperties(['format' => $request->get('format')])->toMediaCollection('videos');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'File cannot be downloaded',
                'code' => '400',
            ], 400);
        }

        return response()->json([
            'message' => 'OK',
            'data' => VideoFormat::make($id),
        ], 201);
    }
}
