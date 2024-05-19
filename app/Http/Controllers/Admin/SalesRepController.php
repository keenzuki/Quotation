<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesRepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reps = User::where('role_id',3)->get();
        return view('admin.salesreps', compact(['reps']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function profile(User $rep)
    {
        $quotations = Quotation::from('quotations as q')
                    ->join('clients as c', function($join) use($rep){
                        $join->on('c.id','=','q.client_id')
                            ->where('c.agent_id',$rep->id);
                    })
                    // ->where('q.client_id',$client->id)
                    ->select('q.id','q.title','q.details','q.cost','q.created_at','q.reference','q.status','q.pay_status')
                    ->orderBy('q.id','DESC')
                    ->get();
        $payments = Payment::from('payments as p')
                    ->join('clients as c', function($join) use($rep){
                        $join->on('c.id','=','p.client_id')
                            ->where('c.agent_id',$rep->id);
                    })
                    // ->where('p.client_id',$client->id)
                    ->select('p.id','p.reference','p.amount','p.mode','p.paid_on as date','p.status',
                        DB::raw("COALESCE(p.bank, 'N/A') as bank"),
                        DB::raw("COALESCE(p.account, 'N/A') as account")
                    )
                    ->orderBy('p.paid_on','DESC')
                    ->get();
        return view('admin.rep_profile', compact('rep','quotations','payments'));
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
    public function show(user $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        //
    }
}
