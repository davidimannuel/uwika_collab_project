<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
  public function create() {
    
    return view('auth.register');
  }
  
  public function store(Request $request) {
    $attributes = $request->validate([
      'name' => ['required'],
      'email' => ['required', 'email:dns', 'unique:users'],
      'password' => ['required', 'min:6', 'confirmed'], // confirmed for password_confirmation
    ]);

    $user = User::create($attributes);
    
    Auth::login($user);
    
    return redirect(route('home'));
  }
}
