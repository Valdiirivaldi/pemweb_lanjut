<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Mengubah status akses kelas untuk seorang siswa (toggle).
     * Jika status aktif → menjadi pending (kelas dikunci).
     * Jika status pending → menjadi active (kelas dibuka).
     * Saat membuka kelas, mencatat waktu (unlocked_at) dan admin yang membuka (unlocked_by).
     * Berguna sebagai audit trail untuk melihat siapa yang membuka akses kelas.
     *
     * @param  int  $id  ID baris pada tabel pivot course_user
     */
    public function toggleAccess(int $id): RedirectResponse
    {
        $row = DB::table('course_user')->where('id', $id)->first();

        abort_if(!$row, 404);

        $currentStatus = $row->status ?? 'pending';
        $isActiveNow = $currentStatus === 'active';

        $newStatus = $isActiveNow ? 'pending' : 'active';
        $newIsUnlocked = $newStatus === 'active' ? 1 : 0;

        $adminId = auth()->id();
        $unlockedAt = $newStatus === 'active' ? now() : null;
        $unlockedBy = $newStatus === 'active' ? $adminId : null;

        DB::table('course_user')->where('id', $id)->update([
            // sinkronkan boolean lama agar middleware tetap bekerja
            'is_unlocked' => $newIsUnlocked,

            // workflow + audit trail
            'status' => $newStatus,
            'unlocked_at' => $unlockedAt,
            'unlocked_by' => $unlockedBy,

            'updated_at' => now(),
        ]);

        return redirect()->route('admin.enrollments.index')
            ->with('success', $newStatus === 'active' ? __('messages.enrollment.opened') : __('messages.enrollment.set_pending'));
    }
}
