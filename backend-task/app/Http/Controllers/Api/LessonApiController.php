<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Course;
use App\Models\Lesson;

class LessonApiController extends Controller
{
    /**
     * Список уроков конкретного курса
     * @param  Course  $course
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Course $course)
    {
        $lessons = $course->lessons;
        return LessonResource::collection($lessons);
    }
}
