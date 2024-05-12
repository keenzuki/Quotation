<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quotation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    // public $user;
    // public function __construct($user){
    //     $this->user = auth()->user();
    // }
    public function index()
    {
        $clients = Client::where('agent_id',auth()->user()->id)->orderBy('id','DESC')->paginate(20);
        return view('clients',compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>['required','regex:/^[a-zA-Z\s]+$/'],
            'phone' => ['required','starts_with:0','min:10','unique:clients,phone'],
            'email' => ['sometimes','email'],
            'address' => ['required','regex:/^[a-zA-Z0-9\s]+$/']
        ]);
        try {
            DB::beginTransaction();
            Client::create([
                'name'=> $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'agent_id' => auth()->user()->id,
                'email' => $request->email ? $request->email: null,
            ]);
            DB::commit();
            return back()->with('success','Cliented registered succefully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error','Oops an error occured. Try again');
        }
    }

    /**
     * Display the specified resource.
     */
    public function profile(Client $client)
    {
        $this->authorize('agentManageClient',$client);
        $user = auth()->user();
        $quotations = Quotation::from('quotations as q')
                    ->join('clients as c', function($join) use($user){
                        $join->on('c.id','=','q.client_id')
                            ->where('c.agent_id',$user->id);
                    })
                    ->where('q.client_id',$client->id)
                    ->select('q.id','q.title','q.details','q.cost','q.created_at','q.reference','q.status','q.pay_status')
                    ->orderBy('q.id','DESC')
                    ->paginate(20);
        $payments = Payment::from('payments as p')
                    ->join('clients as c', function($join) use($user){
                        $join->on('c.id','=','p.client_id')
                            ->where('c.agent_id',$user->id);
                    })
                    ->where('p.client_id',$client->id)
                    ->select('p.id','p.reference','p.amount','p.mode','p.paid_on as date','p.status',
                        DB::raw("COALESCE(p.bank, 'N/A') as bank"),
                        DB::raw("COALESCE(p.account, 'N/A') as account")
                    )
                    ->orderBy('p.paid_on','DESC')
                    ->paginate(20);
        return view('client_profile',compact(['client','payments','quotations']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name'=>['required','regex:/^[a-zA-Z\s]+$/'],
            'phone' => ['required','starts_with:0','min:10',Rule::unique('clients')->ignore($client->id)],
            'email' => ['sometimes','email'],
            'address' => ['required','regex:/^[a-zA-Z0-9\s]+$/']
        ]);
        $this->authorize('agentManageClient', $client);
        try {
            DB::beginTransaction();
            $client->update([
                'name'=> $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
            ]);
            DB::commit();
            return back()->with('success','Client profile updated succefully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error','Oops an error occured. Try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
