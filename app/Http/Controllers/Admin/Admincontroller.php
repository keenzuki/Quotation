<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $clients = Client::orderBy('created_at','DESC')->limit(5)->get();
        $quots = Quotation::where('status',2)->orderBy('created_at','DESC')->limit(5)->get();
        $invs = Quotation::where('status',3)->orderBy('created_at','DESC')->limit(5)->get();
        $pays = Payment::orderBy('created_at','DESC')->limit(5)->get();

        return view('admin.dashboard',compact(['clients','quots','invs','pays']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
