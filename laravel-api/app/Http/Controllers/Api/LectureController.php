<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use Illuminate\Http\Request;
use App\Http\Resources\LectureResource;
use Illuminate\Support\Facades\Validator;
class LectureController extends Controller
{
    public function index(){
        $lectures = Lecture::get();

        if($lectures){
            return LectureResource::collection($lectures);
        } else {
            return response()->json(['message' => ' No record Available'], 200);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required | string | max:255',
            'description' => 'required',
            'video_url' => 'required',
            'category' => 'required | string | max:255',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }


        $lectures = Lecture::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'category' => $request->category,
        ]);

        return response()->json(['message' => 'Lecture Created Successfully', 'data' => new LectureResource($lectures) ],200);
    }
    public function show(Lecture $lecture){
        return new LectureResource($lecture);
    }
    public function update(Lecture $lecture, Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required | string | max:255',
            'description' => 'required',
            'video_url' => 'required',
            'category' => 'required | string | max:255',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }


        $lecture->update([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'category' => $request->category,
        ]);

        return response()->json(['message' => 'Lecture Updated Successfully', 'data' => new LectureResource($lecture) ],200);
    }
    public function destroy(Lecture $lecture){
        $lecture->delete();
        return response()->json(['message' => 'Lecture Deleted Successfully'],200);
    }
}
