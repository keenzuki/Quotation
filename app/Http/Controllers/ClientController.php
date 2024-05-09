<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    ->select('q.id','q.title','q.details','q.cost','q.created_at','q.reference','q.status')
                    ->orderBy('q.id','DESC')
                    ->paginate(20);
        return view('client_profile',compact(['client','quotations']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
