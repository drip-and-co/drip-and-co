<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserPasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function edit()
    {
        return view('user.account-details');
    }

    /**
     * Handle the password update.
     */
    public function update(Request $request)
    {
        // Validate input
        $request->validate([
            'current_password' => ['required'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with(['error' => 'old passwords do not match']);
        }
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'Password changed successfully!');
    }
}