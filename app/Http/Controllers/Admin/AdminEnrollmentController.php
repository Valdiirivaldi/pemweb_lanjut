<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminEnrollmentController extends Controller
{
    public function index(): View
    {

        $siswa  = User::where('role', 'siswa')->orderBy('name')->get();
        $tentors = User::where('role', 'tentor')->orderBy('name')->get();
        $courses = Course::with('tentor')->orderBy('title')->get();

        $status = request('status'); // pending|active|null

        $query = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->select(
                'course_user.id',
                'course_user.is_unlocked',
                'course_user.status',
                'course_user.unlocked_at',
                'course_user.unlocked_by',
                'users.name as user_name',
                'users.email as user_email',
                'courses.title as course_title',
                'courses.tentor_id',
                'course_user.created_at'
            )
            ->orderBy('course_user.created_at', 'desc');

        if (in_array($status, ['pending', 'active'], true)) {
            $query->where('course_user.status', $status);
        }

        $enrollments = $query->get();

        return view('admin.enrollments.index', compact('siswa', 'tentors', 'courses', 'enrollments'));
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $siswa = User::findOrFail($request->user_id);
        abort_if($siswa->role !== 'siswa', 400, 'User yang dipilih bukan siswa.');

        $exists = DB::table('course_user')
            ->where('user_id', $request->user_id)
            ->where('course_id', $request->course_id)
            ->exists();

        if ($exists) {
            return redirect()->route('admin.enrollments.index')
                ->with('error', __('messages.enrollment.exists'));
        }

        DB::table('course_user')->insert([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,

            // sinkronkan workflow
            'is_unlocked' => 0,
            'status' => 'pending',
            'unlocked_at' => null,
            'unlocked_by' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.enrollments.index')
            ->with('success', __('messages.enrollment.granted'));
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
            ->with('success', __('messages.enrollment.assigned', ['course' => $course->title]));
    }

    public function destroy(int $id): RedirectResponse
    {
        DB::table('course_user')->where('id', $id)->delete();


        return redirect()->route('admin.enrollments.index')
            ->with('success', __('messages.enrollment.deleted'));
    }
}
