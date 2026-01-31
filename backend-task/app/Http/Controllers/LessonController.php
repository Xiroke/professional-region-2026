<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Validation\ValidationException;

class LessonController extends Controller
{
    /**
     * Список уроков
     * @param  Course  $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Course $course)
    {
        $lessons = Lesson::all();
        return view('lesson.index', compact('lessons', 'course'));
    }

    /**
     * Страница создания урока
     * @param  Course  $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Course $course)
    {
        return view('lesson.create', compact( 'course'));
    }

    /**
     * Создание урока
     * @param  Course  $course
     * @param  StoreLessonRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Course $course, StoreLessonRequest $request)
    {
        $validated = $request->validated();

        if ($course->lessons()->count() >= 5) {
            throw ValidationException::withMessages(['name' => 'Уроков не может быть больше 5']);
        }

        $validated['course_id'] = $course->id;
        Lesson::create($validated);
        return redirect()->route('courses.lessons.index', $course);
    }

    /**
     * Страница редактирование урока
     * @param  Course  $course
     * @param  Lesson  $lesson
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Course $course, Lesson $lesson)
    {
        return view('lesson.update', compact('lesson', 'course'));
    }

    /**
     * Редактирование урока
     * @param  Course  $course
     * @param  UpdateLessonRequest  $request
     * @param  Lesson  $lesson
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Course $course, UpdateLessonRequest $request, Lesson $lesson)
    {
        $validated = $request->validated();
        $lesson->update($validated);
        return redirect()->route('courses.lessons.index', $course);
    }

    /**
     * Удаление урока
     * @param  Course  $course
     * @param  Lesson  $lesson
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        $lesson->delete();
        return back();
    }
}
