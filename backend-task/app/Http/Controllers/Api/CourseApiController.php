<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseApiController extends Controller
{
    public function reject(Course $course)
    {
        $user = auth()->user();

    }

    public function buy(Course $course)
    {
        $user = auth()->user();

    }

    public function pay()
    {

    }

    public function indexCurrentUser()
    {
        return auth()->user()->courses;
    }

    public function index()
    {
        return Course::paginate(5);
    }

    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();

        $filename = 'mpic_'.Str::random().'.jpg';
        $path = $request->file('img')->storeAs('/images', $filename, 'public');
        $validated['img'] = $path;

        Course::create($validated);
        return response()->noContent();
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $validated = $request->validated();

        if (!empty($validated['img'])) {
            $filename = 'mpic_'.Str::random().'.jpg';
            $path = $request->file('img')->storeAs('/images', $filename, 'public');
            $validated['img'] = $path;
        } else {
            unset($validated['img']);
        }

        $course->update($validated);
        return response()->noContent();
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->noContent();
    }
}
