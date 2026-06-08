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
    /**
     * Menampilkan daftar seluruh pengguna (siswa dan tentor) dengan profil mereka.
     * Mendukung pencarian (search) berdasarkan nama/email dan filter berdasarkan role.
     * Data ditampilkan secara terbatas (10 per halaman) dan diurutkan dari yang terbaru.
     */
    public function index(): View
    {
        $query = User::with(['siswa', 'tentor'])
            ->whereIn('role', ['siswa', 'tentor']);

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = request('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan formulir untuk membuat pengguna baru (siswa atau tentor).
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan pengguna baru ke database dalam satu transaksi.
     * Membuat akun User, lalu membuat profil Siswa atau Tentor sesuai role.
     * Unique ID (S-YYYY-NNNN atau T-YYYY-NNNN) dibuatkan secara otomatis.
     */
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

            if ($user->isSiswa()) {
                Siswa::create([
                    'user_id'   => $user->id,
                    'unique_id' => $this->generateSiswaId(),
                ]);
            } elseif ($user->isTentor()) {
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

    /**
     * Menampilkan formulir untuk mengedit data pengguna siswa atau tentor.
     * Membatalkan aksi jika pengguna bukan siswa atau tentor (403 Forbidden).
     */
    public function edit(int $id): View
    {
        $user = User::with(['siswa', 'tentor'])->findOrFail($id);
        abort_if(!in_array($user->role, ['siswa', 'tentor']), 403);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna yang sudah ada.
     * Jika password diisi, password akan di-hash dan diperbarui.
     * Unique ID juga bisa diubah melalui form ini.
     */
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

        DB::transaction(function () use ($request, $user) {
            $data = [
                'name'  => $request->name,
                'email' => $request->email,
                'role'  => $request->role,
            ];

            if ($request->filled('password')) {
                $request->validate(['password' => ['string', 'min:8']]);
                $data['password'] = Hash::make($request->password);
            }

            $oldRole = $user->getOriginal('role');
            $user->update($data);

            // Sync profile when role changes
            if ($oldRole !== $request->role) {
                if ($oldRole === 'siswa') {
                    $user->siswa?->delete();
                } elseif ($oldRole === 'tentor') {
                    $user->tentor?->delete();
                }

                if ($request->role === 'siswa') {
                    Siswa::create([
                        'user_id'   => $user->id,
                        'unique_id' => $request->unique_id ?? $this->generateSiswaId(),
                    ]);
                } elseif ($request->role === 'tentor') {
                    Tentor::create([
                        'user_id'   => $user->id,
                        'unique_id' => $request->unique_id ?? $this->generateTentorId(),
                    ]);
                }
            } else {
                $record = $user->siswa ?? $user->tentor;
                if ($record && $request->filled('unique_id')) {
                    $record->update(['unique_id' => $request->unique_id]);
                }
            }
        });

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.account.updated'));
    }

    /**
     * Menghapus pengguna (siswa atau tentor) dari database.
     * Hanya bisa menghapus pengguna dengan role siswa atau tentor.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        abort_if(!in_array($user->role, ['siswa', 'tentor']), 403);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.account.deleted'));
    }

    /**
     * Menghasilkan ID unik siswa dengan format S-YYYY-NNNN.
     * Nomer urut diambil dari siswa terakhir pada tahun berjalan, lalu ditambah 1.
     * Menggunakan lockForUpdate untuk mencegah duplikasi data secara bersamaan.
     *
     * @return string  ID unik siswa, contoh: S-2026-0001
     */
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

    /**
     * Menghasilkan ID unik tentor dengan format T-YYYY-NNNN.
     * Nomer urut diambil dari tentor terakhir pada tahun berjalan, lalu ditambah 1.
     * Menggunakan lockForUpdate untuk mencegah duplikasi data secara bersamaan.
     *
     * @return string  ID unik tentor, contoh: T-2026-0001
     */
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
