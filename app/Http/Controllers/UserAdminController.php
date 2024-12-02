<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
  public function index()
  {
    $users = User::where('is_admin',false)->orderBy('updated_at','desc')->paginate(10);
    return view('admin.user.index',[
      'users' => $users,
    ]);
  }

  public function patchStatus(Request $request, $id)
  {
    $user = User::where('id', $id)
      ->where('is_admin',false)->firstOrFail();
    
    if ($user->status == User::STATUS_ACTIVE) {
      $user->status = User::STATUS_INACTIVE;
    } else  {
      $user->status = User::STATUS_ACTIVE;
    }
    $user->save();

    return redirect(route('admin.users.index'));
  }
  
  public function editPassword($id) {
    $user = User::where('id', $id)->firstOrFail();
    return view('admin.user.password',[
      'user' => $user
    ]);
  } 

  public function patchPassword(Request $request, $id)
  {
    $validated = $request->validate([
      'new_password' => 'required|min:8|confirmed',
    ]);

    $user = User::where('id', $id)->firstOrFail();
    $user->password = Hash::make($validated['new_password']);
    $user->save();

    return redirect(route('admin.users.password.edit',$id))->with('success-alert', 'Profile updated successfully.');
  }
}
