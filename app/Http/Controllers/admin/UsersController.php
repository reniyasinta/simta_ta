<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
public function index(Request $request)
{
    $query = User::with('role', 'prodi');

    if ($request->filled('prodi_id')) {
        $query->where('id_prodi', $request->prodi_id);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('created_at', $request->tahun);
    }

    if ($request->filled('search')) {
        $keyword = $request->search;
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%$keyword%")
              ->orWhere('email', 'like', "%$keyword%")
              ->orWhere('nim', 'like', "%$keyword%");
        });
    }

    $users = $query->paginate(20);
    $prodis = Prodi::all();
    $tahunList = User::selectRaw('YEAR(created_at) as tahun')->groupBy('tahun')->pluck('tahun');

    return view('pages.admin.users', compact('users', 'prodis', 'tahunList'));
}

    public function create()
    {
        $roles = Role::all();
        $prodis = Prodi::all();
        return view('pages.admin.create', compact('roles', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'role_id' => 'required|in:1,2,3,4',
            'nim' => 'required_if:role_id,4|nullable|unique:users,nim',
            'nip' => 'required_unless:role_id,4|nullable|unique:users,nip',
            'id_prodi' => 'nullable|exists:prodis,id',
        ], [
            'nim.required_if' => 'NIM wajib diisi untuk mahasiswa.',
            'nip.required_unless' => 'NIP wajib diisi untuk selain mahasiswa.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'nim' => $request->nim,
            'nip' => $request->nip,
            'id_prodi' => $request->id_prodi,
        ]);

        if ($request->role_id == 4) {
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim_mhs' => $request->nim,
                'nama_mhs' => $request->name,
                'id_prodi' => $request->id_prodi,
            ]);
        }

        if ($request->role_id == 3) {
            Dosen::create([
                'user_id' => $user->id,
                'nip_dosen' => $request->nip,
                'nama_dosen' => $request->name,
                'id_prodi' => $request->id_prodi,
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $prodis = Prodi::all();
        return view('pages.admin.edit', compact('user', 'roles', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'role_id' => 'required|in:1,2,3,4',
            'password' => 'nullable|min:4',
            'id_prodi' => 'nullable|exists:prodis,id',
            'nip' => [
                'nullable',
                Rule::unique('users')->ignore($id),
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->role_id, [1, 2, 3]) && !$value) {
                        $fail('NIP wajib diisi untuk peran Admin, Dosen, atau Panitia.');
                    }
                }
            ],
            'nim' => [
                'nullable',
                Rule::unique('users')->ignore($id),
                function ($attribute, $value, $fail) use ($request) {
                    if ((int)$request->role_id === 4 && !$value) {
                        $fail('NIM wajib diisi untuk peran Mahasiswa.');
                    }
                }
            ],
        ]);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->id_prodi = $request->id_prodi;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->nip = in_array($request->role_id, [1, 2, 3]) ? $request->nip : null;
        $user->nim = $request->role_id == 4 ? $request->nim : null;

        $user->save();

        // Sinkronkan dengan data mahasiswa
        if ($user->role_id == 4) {
            if ($user->mahasiswa) {
                $user->mahasiswa->update([
                    'nama_mhs' => $request->name,
                    'nim_mhs' => $request->nim,
                    'id_prodi' => $request->id_prodi,
                ]);
            } else {
                // Jika belum ada entri mahasiswa, buat baru
                \App\Models\Mahasiswa::create([
                    'user_id' => $user->id,
                    'nama_mhs' => $request->name,
                    'nim_mhs' => $request->nim,
                    'id_prodi' => $request->id_prodi,
                ]);
            }
        }

        // Sinkronkan dengan data dosen
        if ($user->role_id == 3) {
            if ($user->dosen) {
                $user->dosen->update([
                    'nama_dosen' => $request->name,
                    'nip_dosen' => $request->nip,
                    'id_prodi' => $request->id_prodi,
                ]);
            } else {
                // Jika belum ada entri dosen, buat baru
                \App\Models\Dosen::create([
                    'user_id' => $user->id,
                    'nama_dosen' => $request->name,
                    'nip_dosen' => $request->nip,
                    'id_prodi' => $request->id_prodi,
                ]);
            }
        }

        return redirect()->route('admin.users')->with('success', 'User & data terkait berhasil diperbarui.');
    }



    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    public function importForm()
    {
        return view('pages.admin.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new UsersImport, $request->file('file_excel'));

        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diimport!');
    }
}
