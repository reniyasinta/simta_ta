<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

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
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role_id' => 'required',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role_id' => $request->role_id,
    ]);

    return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
}

public function edit($id)
{
    $user = User::findOrFail($id);
    $roles = Role::all();
    return view('pages.admin.edit', compact('user', 'roles'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'role_id' => 'required',
    ]);

    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role_id = $request->role_id;

    if ($request->filled('password')) {
        $request->validate(['password' => 'min:8']);
        $user->password = bcrypt($request->password);
    }

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

    return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diimport!');
}

}
