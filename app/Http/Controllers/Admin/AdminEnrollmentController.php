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
    /**
     * Menampilkan halaman manajemen pendaftaran (enrollment) siswa ke kelas.
     * Menyediakan data siswa, tentor, dan kelas untuk form.
     * Mendukung filter berdasarkan status (pending/active) dan pencarian via query parameter.
     */
    public function index(): View
    {

        $siswa  = User::where('role', 'siswa')->whereHas('siswa')->orderBy('name')->get();
        $tentors = User::where('role', 'tentor')->whereHas('tentor')->orderBy('name')->get();
        $courses = Course::with('tentor')->orderBy('title')->get();

        $status = request('status');
        $search = request('search');

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

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('courses.title', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->get();

        return view('admin.enrollments.index', compact('siswa', 'tentors', 'courses', 'enrollments'));
    }


    /**
     * Mendaftarkan (enroll) seorang siswa ke sebuah kelas/kursus.
     * Status awal adalah 'pending' dan kelas belum terbuka (is_unlocked = 0).
     * Mencegah pendaftaran ganda (sama siswa di kelas yang sama).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $siswa = User::findOrFail($request->user_id);
        abort_if(!$siswa->isSiswa(), 400, 'User yang dipilih bukan siswa.');

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


    /**
     * Menugaskan (assign) seorang tentor ke sebuah kelas/kursus.
     * Memperbarui kolom tentor_id pada tabel courses.
     */
    public function assignTentor(Request $request): RedirectResponse
    {
        $request->validate([
            'tentor_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $tentor = User::findOrFail($request->tentor_id);
        abort_if(!$tentor->isTentor(), 400, 'User yang dipilih bukan tentor.');

        $course = Course::findOrFail($request->course_id);
        $course->tentor_id = $request->tentor_id;
        $course->save();

        return redirect()->route('admin.enrollments.index', ['tab' => 'tentor'])
            ->with('success', __('messages.enrollment.assigned', ['course' => $course->title]));
    }

    /**
     * Menghapus pendaftaran (enrollment) siswa dari kelas.
     * Baris pada tabel pivot course_user akan dihapus secara permanen.
     */
    public function destroy(int $id): RedirectResponse
    {
        DB::table('course_user')->where('id', $id)->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', __('messages.enrollment.deleted'));
    }

    /**
     * Mengubah status akses secara massal (bulk toggle) untuk beberapa enrollment.
     * Menerima array ID enrollment dan status target (active/pending).
     */
    public function bulkToggleAccess(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:course_user,id'],
            'action' => ['required', 'in:lock,unlock'],
        ]);

        $isUnlock = $request->action === 'unlock';
        $newStatus = $isUnlock ? 'active' : 'pending';
        $adminId = auth()->id();

        $now = now();

        DB::table('course_user')
            ->whereIn('id', $request->ids)
            ->update([
                'is_unlocked'  => $isUnlock ? 1 : 0,
                'status'       => $newStatus,
                'unlocked_at'  => $isUnlock ? $now : null,
                'unlocked_by'  => $isUnlock ? $adminId : null,
                'updated_at'   => $now,
            ]);

        $count = count($request->ids);
        $msg = $isUnlock
            ? "{$count} enrollment(s) telah dibuka."
            : "{$count} enrollment(s) telah dikunci.";

        return redirect()->route('admin.enrollments.index')
            ->with('success', $msg);
    }
}
