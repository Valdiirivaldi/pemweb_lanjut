<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Menampilkan daftar semua kelas/kursus yang diampu oleh tentor ini.
     * Setiap kelas dilengkapi dengan jumlah modul, kuis, dan siswa.
     */
    public function index(): View
    {
        $user = Auth::user();
        $courses = $user->courses()->withCount('modules', 'quizzes', 'students')->latest()->get();

        return view('tentor.courses.index', compact('user', 'courses'));
    }

    /**
     * Menampilkan formulir untuk membuat kelas/kursus baru.
     */
    public function create(): View
    {
        return view('tentor.courses.create');
    }

    /**
     * Menyimpan kelas/kursus baru ke database.
     * Tentor_id diisi otomatis dengan ID pengguna yang sedang login.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        Course::create([
            'title'       => $request->title,
            'description' => $request->description,
            'tentor_id'   => Auth::id(),
        ]);

        return redirect()->route('tentor.courses.index')
            ->with('success', __('messages.course.created'));
    }

    /**
     * Menampilkan detail satu kelas/kursus beserta daftar modulnya.
     * Hanya bisa diakses oleh tentor yang memiliki kelas tersebut (403 jika bukan pemilik).
     */
    public function show(Course $course): View
    {
        $user = Auth::user();

        if ($course->tentor_id !== $user->id) {
            abort(403);
        }

        $course->load(['modules' => function ($q) {
            $q->withCount('submissions')->latest();
        }]);

        return view('tentor.courses.show', compact('user', 'course'));
    }
}
