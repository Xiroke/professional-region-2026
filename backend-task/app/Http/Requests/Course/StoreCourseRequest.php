<?php

namespace app\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\File;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:30',
            'description' => 'nullable|string|min:1|max:100',
            'hours' => 'required|integer|max:10',
            'price' => 'required|decimal:0,2|min:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'img' => ['required', File::types(['jpg', 'jpeg'])->max('2mb')],
        ];
    }
}
