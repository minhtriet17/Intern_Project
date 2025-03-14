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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $subject = Subject::create($request->all());
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $subject->update($request->all());
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