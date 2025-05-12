<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;



class UsersController extends Controller
{
    public function index()
    {
        // Ambil semua user beserta relasi role-nya
        $users = User::with('role')->get();

        return view('pages.admin.users', compact('users'));
    }
    public function create()
{
    $roles = Role::all();
    return view('pages.admin.create', compact('roles'));
}

public function store(Request $request)
{

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:4',
        'role_id' => 'required|in:1,2,3,4',

        // Validasi dinamis berdasarkan role
        'nim' => 'required_if:role_id,4|nullable|unique:users,nim',
        'nip' => 'required_unless:role_id,4|nullable|unique:users,nip',
    ], [
        'nim.required_if' => 'NIM wajib diisi untuk mahasiswa.',
        'nip.required_unless' => 'NIP wajib diisi untuk selain mahasiswa.',
    ]);
        // Menambahkan pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'nim' => $request->nim,  // Jika ada NIM, akan disimpan
            'nip' => $request->nip,  // Jika ada NIP, akan disimpan
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');
    }


public function edit($id)
{
    $user = User::findOrFail($id);
    $roles = Role::all();
    return view('pages.admin.edit', compact('user', 'roles'));
}
public function update(Request $request, $id)
{
    // Ambil data user
    $user = User::findOrFail($id);

    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($id),
        ],
        'role_id' => 'required|in:1,2,3,4',
        'password' => 'nullable|min:8',
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

    // Jika ada password baru
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Atur NIP dan NIM berdasarkan role
    $user->nip = in_array($request->role_id, [1, 2, 3]) ? $request->nip : null;
    $user->nim = $request->role_id == 4 ? $request->nim : null;

    $user->save();

    return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
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

    return redirect()->route('admin.users')->with('success', 'Data user berhasil diimport!');
}

}


