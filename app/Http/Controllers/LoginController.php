<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  public function create() {
    
    return view('auth.login');
  }
  
  public function store(Request $request) {
    $attributes = $request->validate([
      'email' => ['required','email'],
      'password' => ['required'],
    ]);
    
    $remember = $request->has('remember');
    
    if (!Auth::attempt($attributes,$remember)) {
      throw ValidationException::withMessages([
        "email" => 'Invalid credentials'
      ]);
    }

    $request->session()->regenerate();

    return redirect(route('home'));
  }
  
  public function destroy() {
    Auth::logout();

    return redirect(route('home'));
  }
}
