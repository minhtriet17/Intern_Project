<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    // Get all subjects
    public function index()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }

    // Store a new subject
    public function store(Request $request)
    { 
        \Log::info('Request data:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('thumbnail')) { 
            $imagePath = $request->file('thumbnail')->store('thumbnails', 'public');
            \Log::info('Generated image path:', ['path' => $imagePath]); // Debug đường dẫn ảnh
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $subject = Subject::create([
            'name' => $request->name,
            'description' => $request->description,
            'thumbnail' => $imagePath ? asset('storage/' . $imagePath) : null,
        ]);

        // $subject = Subject::create($request->all());
        return response()->json($subject, 201);
    }

    // Show a specific subject
    public function show($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        return response()->json($subject);
    }

    // Update a specific subject
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        // Xử lý cập nhật ảnh nếu có ảnh mới
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($subject->thumbnail) {
                $oldImagePath = str_replace(asset('storage/'), '', $subject->thumbnail);
                \Storage::disk('public')->delete($oldImagePath);
            }

            // Lưu ảnh mới
            $imagePath = $request->file('thumbnail')->store('thumbnails', 'public');
            $subject->thumbnail = asset('storage/' . $imagePath);
        }

        // Cập nhật thông tin khác
        $subject->name = $request->name;
        $subject->description = $request->description;
        
        $subject->save();

        // $subject->update($request->all());
        return response()->json($subject);
    }

    // Delete a specific subject
    public function destroy($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $subject->delete();
        return response()->json(['message' => 'Subject deleted successfully']);
    }
}