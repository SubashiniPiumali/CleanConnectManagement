<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileData;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user(); 
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'gender' => 'required|in:male,female',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
        ]);
        

        return redirect('/')->with('success', 'Profile updated successfully.');
    }

}