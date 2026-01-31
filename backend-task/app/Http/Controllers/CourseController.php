<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Список курсов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $courses = Course::paginate(5);
        return view('course.index', compact('courses'));
    }

    /**
     * Страница создания курса
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('course.create');
    }

    /**
     * Создание курса
     * @param  StoreCourseRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();
        $file = $request->file('img');
        $validated['img'] = Course::resizeImage($file);

        Course::create($validated);
        return redirect()->route('courses.index');
    }

    /**
     * Страница редактирование курса
     * @param  Course  $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Course $course)
    {
        return view('course.update', compact('course'));
    }

    /**
     * Редактирование курса
     * @param  UpdateCourseRequest  $request
     * @param  Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $validated = $request->validated();

        if (!empty($validated['img'])) {
            $file = $request->file('img');
            $validated['img'] = Course::resizeImage($file);
        } else {
            unset($validated['img']);
        }

        $course->update($validated);
        return redirect()->route('courses.index');
    }

    /**
     * Удаление курса
     * @param  Course  $course
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $course)
    {
        if ($course->users()->count() >= 1) {
            return back()->withErrors(['message' => 'На курс уже записаны студенты']);
        }

        $course->delete();
        return back();
    }
}
