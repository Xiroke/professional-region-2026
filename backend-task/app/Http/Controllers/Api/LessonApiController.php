<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        return Lesson::all();
    }

    public function create()
    {
        return view('lesson.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validated();
        Lesson::create($validated);
        return response()->noContent();
    }

    public function show(Lesson $lesson)
    {
        return $lesson;
    }

    public function edit(Lesson $lesson)
    {
        return view('lesson.update', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validated();
        $lesson->update($validated);
        return response()->noContent();
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return response()->noContent();
    }
}
