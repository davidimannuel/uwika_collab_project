<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function updateProfile(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'current_password' => ['required_if:new_password,!=,'], // Only required if new_password is provided
      'new_password' => 'nullable|min:8|confirmed',
    ]);

    // Update the user's name
    $user = Auth::user();
    $user->name = $validated['name'];

   // If a new password is provided, validate the current password and update the password
   if ($request->filled('new_password')) {
      // Check if the provided current password is correct
      if (!Hash::check($validated['current_password'], $user->password)) {
          return back()->withErrors(['current_password' => 'The current password is incorrect.']);
      }

      // Update the password
      $user->password = Hash::make($validated['new_password']);
    } 

    $user->save();
  
    return redirect()->route('profile')->with('success-alert', 'Profile updated successfully.');
  }  
}
