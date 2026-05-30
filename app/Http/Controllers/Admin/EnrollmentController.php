<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function toggleAccess(int $id): RedirectResponse
    {
        $row = DB::table('course_user')->where('id', $id)->first();

        abort_if(!$row, 404);

        $newValue = (int) (!$row->is_unlocked);

        DB::table('course_user')->where('id', $id)->update([
            'is_unlocked' => $newValue,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.enrollments.index')
            ->with('success', $newValue ? 'Akses dibuka.' : 'Akses dikunci.');
    }
}
