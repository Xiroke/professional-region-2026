<?php

namespace App\Http\Controllers\Api;

use App\Enum\PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\CoursePaginationCollection;
use App\Http\Resources\CourseResource;
use App\Http\Resources\OrderCollection;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Support\Str;

class CourseApiController extends Controller
{
    /**
     * Отмена записи на курс
     * @param  int  $order_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(int $order_id)
    {
        $order = Order::find($order_id);

        if ($order->payment_status == PaymentStatusEnum::success->value) {
            return response()->json(['status' => 'was payed', 418]);
        }

        $order->update(['payment_status' => PaymentStatusEnum::failed->value]);
        return response()->json(['status' => 'success']);
    }

    /**
     * Покупка курса
     * @param  Course  $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function buy(Course $course)
    {
        $user = auth()->user();
        $user->courses()->attach($course->id, ['payment_status' => PaymentStatusEnum::pending->value]);
        return response()->json(['payment_url' => 'https://example.com']);
    }

    /**
     * Оплата курса
     * @param  PaymentRequest  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function pay(PaymentRequest $request)
    {
        $validated = $request->validated();
        $order = Order::find($validated['order_id']);
        $course = Course::find($order->course_id);

        if ($course->start_date < now()) {
            return response()->json(['message' => 'Курс уже начался'], 422);
        }

        if ($validated['status'] == 'success') {
            $order->update(['payment_status' => PaymentStatusEnum::success->value]);
        } else {
            $order->update(['payment_status' => PaymentStatusEnum::failed->value]);
        }
        return response()->noContent();
    }

    /**
     * Список курса студентов
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexCurrentUser()
    {
        $courses = auth()->user()->courses;

        $data = $courses->map(function ($i) {
            return [
                'id' => $i->pivot->id,
                'payment_status' => Order::getRuStatus($i->pivot->payment_status),
                'course' => (new CourseResource($i))->resolve()
            ];
        });

        return response()->json(compact('data'));
    }

    /**
     * Список курсов
     * @return CoursePaginationCollection
     */
    public function index()
    {
        return new CoursePaginationCollection(Course::paginate(5));
    }
}
