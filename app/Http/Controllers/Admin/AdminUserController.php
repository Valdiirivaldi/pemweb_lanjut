<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Tentor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['siswa', 'tentor'])
            ->whereIn('role', ['siswa', 'tentor'])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'in:siswa,tentor'],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            if ($user->role === 'siswa') {
                Siswa::create([
                    'user_id'   => $user->id,
                    'unique_id' => $this->generateSiswaId(),
                ]);
            } elseif ($user->role === 'tentor') {
                Tentor::create([
                    'user_id'   => $user->id,
                    'unique_id' => $this->generateTentorId(),
                ]);
            }

            return $user;
        });

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.account.created'));
    }

    public function edit(int $id): View
    {
        $user = User::with(['siswa', 'tentor'])->findOrFail($id);
        abort_if(!in_array($user->role, ['siswa', 'tentor']), 403);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::with(['siswa', 'tentor'])->findOrFail($id);
        abort_if(!in_array($user->role, ['siswa', 'tentor']), 403);

        $uniqueIdRules = ['nullable', 'string', 'max:20'];

        $record = $user->siswa ?? $user->tentor;
        if ($record) {
            $uniqueIdRules[] = 'unique:siswas,unique_id,' . $record->id;
            $uniqueIdRules[] = 'unique:tentors,unique_id,' . $record->id;
        }

        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role'      => ['required', 'in:siswa,tentor'],
            'unique_id' => $uniqueIdRules,
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8']]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($record && $request->filled('unique_id')) {
            $record->update(['unique_id' => $request->unique_id]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.account.updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        abort_if(!in_array($user->role, ['siswa', 'tentor']), 403);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.account.deleted'));
    }

    private function generateSiswaId(): string
    {
        $year = date('Y');
        $last = Siswa::where('unique_id', 'like', "S-{$year}-%")
            ->orderBy('unique_id', 'desc')
            ->lockForUpdate()
            ->value('unique_id');

        if ($last) {
            $num = (int) substr($last, -4) + 1;
        } else {
            $num = 1;
        }

        return 'S-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    private function generateTentorId(): string
    {
        $year = date('Y');
        $last = Tentor::where('unique_id', 'like', "T-{$year}-%")
            ->orderBy('unique_id', 'desc')
            ->lockForUpdate()
            ->value('unique_id');

        if ($last) {
            $num = (int) substr($last, -4) + 1;
        } else {
            $num = 1;
        }

        return 'T-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
