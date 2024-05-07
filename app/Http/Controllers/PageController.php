<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard(){
        $user = auth()->user();
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard');
        }elseif ($user->role_id == 2) {
            return redirect()->route('admin.dashboard');
        }elseif ($user->role_id == 3) {
            return redirect()->route('agent.dashboard');
        }else {
            return redirect()->route('home');
        }
    }
}
