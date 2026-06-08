<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Menampilkan daftar semua sertifikat yang dimiliki siswa.
     * Sertifikat diambil dari percobaan kuis yang menghasilkan certificate_path tidak null.
     * Setiap sertifikat dilengkapi informasi kuis dan kelas terkait.
     */
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
