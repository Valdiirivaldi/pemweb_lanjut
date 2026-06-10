<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
    public function toggleAccess(int $id): RedirectResponse|JsonResponse
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
            'is_unlocked' => $newIsUnlocked,
            'status' => $newStatus,
            'unlocked_at' => $unlockedAt,
            'unlocked_by' => $unlockedBy,
            'updated_at' => now(),
        ]);

        $message = $newStatus === 'active'
            ? __('messages.enrollment.opened')
            : __('messages.enrollment.set_pending');

        if (request()->ajax()) {
            $unlockedByName = $newStatus === 'active' && $adminId
                ? e(User::find($adminId)?->name ?? 'User #' . $adminId)
                : null;

            $dt = $newStatus === 'active' ? now()->format('d M Y H:i') : null;

            $lockedLabel = $newStatus === 'active' ? 'Lock Access' : 'Unlock Access';
            $lockedIcon  = $newStatus === 'active' ? 'lock' : 'unlock';
            $lockedColor = $newStatus === 'active' ? '#e74c3c' : '#27ae60';

            $statusClass = $newStatus === 'active' ? 'active' : 'pending';
            $statusLabel = $newStatus === 'active' ? 'Active' : 'Pending';

            return response()->json([
                'success' => true,
                'message' => $message,
                'row' => [
                    'status_html' => '<span class="badge-status ' . $statusClass . '"><i data-lucide="circle" style="width:6px;height:6px;"></i> ' . $statusLabel . '</span>',
                    'unlocked_by_html' => $newStatus === 'active'
                        ? e($unlockedByName) . '<br><small style="font-size:0.7rem;">' . e($dt) . '</small>'
                        : '<span class="text-muted">—</span>',
                    'actions_html' => '<form action="' . route('admin.enrollments.toggle-access', $id) . '" method="POST" style="display:inline">
                            ' . csrf_field() . '
                            <button type="submit" class="btn-action-icon ' . ($newStatus === 'active' ? 'lock' : 'unlock') . '" data-ajax-action="toggle-access" data-confirm="' . ($newStatus === 'active' ? 'Lock access for this enrollment?' : 'Unlock access for this enrollment?') . '" title="' . $lockedLabel . '">
                                <i data-lucide="' . $lockedIcon . '"></i>
                            </button>
                        </form>
                        <form action="' . route('admin.enrollments.destroy', $id) . '" method="POST" style="display:inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn-action-icon delete" data-ajax-action="delete" data-confirm="Delete this enrollment?" title="Delete">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </form>',
                ]
            ]);
        }

        return redirect()->route('admin.enrollments.index')->with('success', $message);
    }
}
