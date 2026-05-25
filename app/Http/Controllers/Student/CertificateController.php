<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $certificates = $user->quizAttempts()
            ->whereNotNull('certificate_path')
            ->with('quiz.course')
            ->latest()
            ->get();

        return view('student.certificates.index', compact('user', 'certificates'));
    }
}
