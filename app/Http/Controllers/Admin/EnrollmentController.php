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
                    'actions_html' => '<div class="dropdown">
                        <button class="btn-action-icon" data-bs-toggle="dropdown" title="Actions">
                            <i data-lucide="more-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius:12px;border:none;padding:6px;min-width:160px;">
                            <li>
                                <form action="' . route('admin.enrollments.toggle-access', $id) . '" method="POST">
                                    ' . csrf_field() . '
                                    <button type="submit" class="dropdown-item py-2 rounded-2" data-ajax-action="toggle-access" data-confirm="' . ($newStatus === 'active' ? 'Lock access for this enrollment?' : 'Unlock access for this enrollment?') . '">
                                        <i data-lucide="' . $lockedIcon . '" style="width:14px;height:14px;margin-right:8px;color:' . $lockedColor . ';"></i>
                                        ' . $lockedLabel . '
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="' . route('admin.enrollments.destroy', $id) . '" method="POST">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="submit" class="dropdown-item py-2 rounded-2 text-danger" data-ajax-action="delete" data-confirm="Delete this enrollment?">
                                        <i data-lucide="trash-2" style="width:14px;height:14px;margin-right:8px;"></i>Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>',
                ]
            ]);
        }

        return redirect()->route('admin.enrollments.index')->with('success', $message);
    }
}
