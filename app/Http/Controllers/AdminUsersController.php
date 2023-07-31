<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    function index()
    {
        return view('admin.pages.adminusers.login');
    }
    function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $users = User::all();
        foreach($users as $user)
        {
            if($username==($user->username)&&$password==($user->password)&&($user->role)=="admin"){
                $userid=$user->id;
               $username = $user->username;
                $request->session()->put('id',$userid);
                $request->session()->put('username',$username);
                 
                return redirect()->route('admin.index');
            }
        }
      
    }
}
