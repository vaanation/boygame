<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.security.index', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.']);
            }
            $admin->password = Hash::make($request->password);
        }

        $admin->email = $request->email;
        $admin->save();

        return back()->with('success', 'Keamanan akun berhasil diperbarui.');
    }
}
