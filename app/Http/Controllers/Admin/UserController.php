<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    public function filterUsers($role){
        if ($role == 0) {
            $filtered = User::from('users as u')
                        ->leftJoin('roles as r','r.id','=','u.role')
                        ->select('u.status as status','u.id','u.fname','u.lname','r.name as role')  
                        ->get();
        } else {
            $rolei = Role::where('id',$role)->first();
            $filtered = User::from('users as u')
                        ->leftJoin('roles as r','r.id','=','u.role')
                        ->where('role', $rolei->id)
                        ->select('u.status as status','u.id','u.fname','u.lname','r.name as role')  
                        ->get();
        }
        $response = [
            'users' => $filtered
        ];
        return response()->json($response);
    }
    // search specific user
    public function searchUser(Request $request){
        $input = $request->search;
        if ($input == 0) {
            $searched = User::from('users as u')
                        ->leftJoin('roles as r','r.id','=','u.role')
                        ->select('u.status as status','u.id','u.fname','u.lname','r.name as role')  
                        ->get();
        } else {
            // $inputi = Role::where('id',$input)->first();
            $searched = User::from('users as u')
                        ->leftJoin('roles as r','r.id','=','u.role')
                        ->where('u.fname','LIKE', "$input%")
                        ->orWhere('u.lname','LIKE', "$input%")
                        ->select('u.status as status','u.id','u.fname','u.lname','r.name as role','u.role as roleId')  
                        ->get();
        }
        return response()->json(['users'=>$searched]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
