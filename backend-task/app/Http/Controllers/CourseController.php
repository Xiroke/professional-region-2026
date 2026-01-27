<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return Course::all();
    }

    public function create()
    {
        return view('course.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validated();
        Course::create($validated);
        return response()->noContent();
    }

    public function show(Course $course)
    {
        return $course;
    }

    public function edit(Course $course)
    {
        return view('course.update', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validated();
        $course->update($validated);
        return response()->noContent();
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->noContent();
    }
}
