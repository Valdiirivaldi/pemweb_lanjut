<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminEnrollmentController extends Controller
{
    public function index(): View
    {
        $siswa  = User::where('role', 'siswa')->orderBy('name')->get();
        $tentors = User::where('role', 'tentor')->orderBy('name')->get();
        $courses = Course::with('tentor')->orderBy('title')->get();

        $enrollments = \DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->select(
                'course_user.id',
                'users.name as user_name',
                'users.email as user_email',
                'courses.title as course_title',
                'courses.tentor_id',
                'course_user.created_at'
            )
            ->orderBy('course_user.created_at', 'desc')
            ->get();

        return view('admin.enrollments.index', compact('siswa', 'tentors', 'courses', 'enrollments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id'   => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $siswa = User::findOrFail($request->user_id);
        abort_if($siswa->role !== 'siswa', 400, 'User yang dipilih bukan siswa.');

        $exists = \DB::table('course_user')
            ->where('user_id', $request->user_id)
            ->where('course_id', $request->course_id)
            ->exists();

        if ($exists) {
            return redirect()->route('admin.enrollments.index')
                ->with('error', 'Siswa sudah terdaftar di kelas ini.');
        }

        \DB::table('course_user')->insert([
            'user_id'    => $request->user_id,
            'course_id'  => $request->course_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Akses kelas berhasil diberikan kepada siswa!');
    }

    public function assignTentor(Request $request): RedirectResponse
    {
        $request->validate([
            'tentor_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $tentor = User::findOrFail($request->tentor_id);
        abort_if($tentor->role !== 'tentor', 400, 'User yang dipilih bukan tentor.');

        $course = Course::findOrFail($request->course_id);
        $course->tentor_id = $request->tentor_id;
        $course->save();

        return redirect()->route('admin.enrollments.index', ['tab' => 'tentor'])
            ->with('success', 'Tentor berhasil ditugaskan ke kelas "' . $course->title . '".');
    }

    public function destroy(int $id): RedirectResponse
    {
        \DB::table('course_user')->where('id', $id)->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment berhasil dihapus.');
    }
}
