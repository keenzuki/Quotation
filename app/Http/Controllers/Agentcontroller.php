<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $user = auth()->user();
        $p = Quotation::from('quotations as q')
                ->join('clients as c', function($join) use($user){
                    $join->on('q.client_id','c.id')
                    ->where('c.agent_id',$user->id);
                })
                ->select(
                    DB::raw("COALESCE(SUM(CASE WHEN q.status = 1 THEN 1 ELSE 0 END),0) as drafts"),
                    DB::raw("COALESCE(SUM(CASE WHEN q.status = 2 THEN 1 ELSE 0 END),0) as quotations"),
                    DB::raw("COALESCE(SUM(CASE WHEN q.status = 3 THEN 1 ELSE 0 END),0) as invoices"),
                )->first();
        return view('dashboard', compact('p'));
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
