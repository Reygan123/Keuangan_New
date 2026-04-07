<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateUsersController extends Controller
{
    public function index(Request $request)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $query = User::query();

        $users = $query->latest()->get();

        return view('super_admin.Users.index', compact('users'));
    }

     public function store(Request $request)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:super_admin,admin,koperasi',
            'password' => 'nullable|string',
        ]);

        ModelsUser::create($validated);
        return redirect()->route('super_admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

     public function update(Request $request, ModelsUser $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:super_admin,admin,koperasi',
        ]);

        $user->update($validated);

        return redirect()->route('super_admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = ModelsUser::findOrFail($id);
        $user->delete();
        return redirect()->route('super_admin.users.index')->with('success', 'User berhasil dihapus.');
    }

}
