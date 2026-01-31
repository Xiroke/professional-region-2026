<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Страница печати сертификата по шаблону
     * @param  Course  $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function print_page(Course $course)
    {
        $user = auth()->user();
        return view('certificate', compact('user', 'course'));
    }
}
