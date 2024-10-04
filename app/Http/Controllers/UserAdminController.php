<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
