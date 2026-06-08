<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Menampilkan daftar semua siswa yang terdaftar di kelas-kelas milik tentor ini.
     * Setiap siswa dilengkapi informasi kelas mana yang diikutinya.
     * Siswa yang mengikuti beberapa kelas hanya ditampilkan sekali (unique by ID).
     */
    public function index()
    {
        $user = Auth::user();
        $courses = $user->courses()->with('students')->get();
        $students = $courses->flatMap(function ($course) {
            return $course->students->map(function ($student) use ($course) {
                $student->enrolled_course = $course->title;
                $student->enrolled_course_id = $course->id;
                return $student;
            });
        })->unique('id')->values();

        return view('tentor.students.index', compact('user', 'students', 'courses'));
    }
}
