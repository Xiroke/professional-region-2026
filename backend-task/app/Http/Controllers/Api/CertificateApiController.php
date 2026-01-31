<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certificate\CheckCertificateRequest;
use App\Http\Requests\Certificate\StoreCertificateRequest;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class CertificateApiController extends Controller
{
    /**
     * Проверка сертификата
     * @param  CheckCertificateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCertificate(CheckCertificateRequest $request)
    {
        $validated = $request->validated();

        $status = $validated['sertikate_number'][11] ? 'success' : 'failed';
        return response()->json(compact('status'));
    }

    /**
     * Создание сертификата
     * @param  StoreCertificateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCertificate(StoreCertificateRequest $request)
    {
        return response()->json(['course_number' => rand(10000000000, 99999999999).'1']);
    }
}
