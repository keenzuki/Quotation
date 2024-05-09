<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Client;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(){
        $user = auth()->user();
        $quotations = Quotation::from('quotations as q')
                    ->join('clients as c',function ($join) use($user){
                        $join->on('q.client_id','=','c.id')
                        ->where('c.agent_id',$user->id);
                    })
                    ->where('status','!=',3)
                    ->select('q.*','c.name as client')
                    ->orderBy('q.id','DESC')
                    ->paginate(20);
        return view('quotations',compact(['quotations']));
    }
    public function quotation(Quotation $quotation)
    {
        $client = $quotation->client;
        $this->authorize('agentManageClient',$client);
        return view('view_quotation',compact(['quotation']));
    }
    public function invoices(){
        $invoices = Quotation::where('status',true)->paginate(20);
     
    }
    public function create(Client $client)
    {
        return view('create_quotation',compact(['client']));
    }
    public function makeQuotation()
    {
        $clients=Client::where('agent_id',auth()->user()->id)->get();
        return view('make_quotation',compact(['clients']));
    }
    public function storeDraft(Request $request){
        $request->validate([
            'client' => 'required|exists:clients,id',
            'items.*' => 'required',
            'quantities.*' => 'nullable|min:1',
            'units.*' => 'nullable',
            'prices.*' => 'nullable|min:0',
            'comments' => 'nullable', // Allow comments to be optional for drafts
        ]);        
        try {
            DB::beginTransaction();
            $quotation = Quotation::create([
                'reference' => Quotation::genref(),
                'client_id' => $request->client,
                'title' =>$request->title,
                'status' => 1,
                'details' => $request->comments
            ]);
            $total = 0;
            foreach ($request->items as $key => $item) {
                Sales::create([
                    'quot_id' => $quotation->id,
                    'name' => $request->items[$key],
                    'quantity' => $request->quantities[$key],
                    'unit' => $request->units[$key],
                    'price' => $request->prices[$key],
                ]);
                $total += ($request->quantities[$key] * $request->prices[$key]);
            }
            $quotation->update([
                'cost' => $total
            ]);
            DB::commit();
            return redirect()->route('agent.quotations')->with('success','Quotation stored successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('error','An error occured');
        }
    }
    public function editDraft(Quotation $quotation){
        $client = $quotation->client;
        $this->authorize('agentManageClient', $client);
        return view('edit_draft',compact(['quotation']));
    }
    public function updateQuotation2Invoice(Quotation $quotation){
        $client = $quotation->client;
        $this->authorize('agentManageClient', $client);
        $quotation->update([
            'status' => 3 //Invoice status code
        ]);
       return back()->with('success','Quotation converted successfully');
    }
    public function editQuotation(Quotation $quotation){
        $client = $quotation->client;
        $this->authorize('agentManageClient', $client);
        return view('edit_quotation',compact(['quotation']));
    }
    public function updateQuotation(Request $request,Quotation $quotation){
        // dd($request);
        $request->validate([
            'items.*' => 'required',
            'quantities.*' => 'required|min:1',
            'units.*' => 'required',
            'prices.*' => 'required|min:0',
            'comments' => 'required'
        ]);
        $client=$quotation->client;
        $this->authorize('agentManageClient', $client);
        try {
            DB::beginTransaction();
            // $quotation->update([
            //     // 'reference' => Quotation::genref(),
            //     // 'client_id' => $request->client,
            //     'title' =>$request->title,
            //     'status' => 2,
            //     'details' => $request->comments
            // ]);
            foreach ($quotation->sales as $key => $item) {
                $item->delete();
            }
            $total = 0;
            foreach ($request->items as $key => $item) {
                Sales::create([
                    'quot_id' => $quotation->id,
                    'name' => $request->items[$key],
                    'quantity' => $request->quantities[$key],
                    'unit' => $request->units[$key],
                    'price' => $request->prices[$key],
                ]);
                $total += ($request->quantities[$key] * $request->prices[$key]);
            }
            $quotation->update([
                'title' =>$request->title,
                'status' => 2,
                'details' => $request->comments,
                'cost' => $total
            ]);
            DB::commit();
            return redirect()->route('agent.quotations')->with('success','Quotation updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()-> with('error','An error occured');
        }
    }
    public function completeDraft(Request $request,Quotation $quotation){
        $request->validate([
            'items.*' => 'required',
            'quantities.*' => 'required|min:1',
            'units.*' => 'required',
            'prices.*' => 'required|min:0',
            'comments' => 'required'
        ]);
        $client=$quotation->client;
        $this->authorize('agentManageClient', $client);
        
        try {
            DB::beginTransaction();
            foreach ($quotation->sales as $key => $item) {
                $item->delete();
            }
            $total = 0;
            foreach ($request->items as $key => $item) {
                Sales::create([
                    'quot_id' => $quotation->id,
                    'name' => $request->items[$key],
                    'quantity' => $request->quantities[$key],
                    'unit' => $request->units[$key],
                    'price' => $request->prices[$key],
                ]);
                $total += ($request->quantities[$key] * $request->prices[$key]);
            }
            $quotation->update([
                'title' =>$request->title,
                'status' => 2,
                'details' => $request->comments,
                'cost' => $total
            ]);
            DB::commit();
            return redirect()->route('agent.quotations')->with('success','Draft saved as Quotation successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()-> with('error','An error occured');
        }
    }

    public function storeQuotation(Request $request){
        $request->validate([
            'client' => 'required|exists:clients,id',
            'items.*' => 'required',
            'quantities.*' => 'required|min:1',
            'units.*' => 'required',
            'prices.*' => 'required|min:0',
            'comments' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $quotation = Quotation::create([
                'reference' => Quotation::genref(),
                'client_id' => $request->client,
                'title' =>$request->title,
                'status' => 2,
                'details' => $request->comments
            ]);
            $total = 0;
            foreach ($request->items as $key => $item) {
                Sales::create([
                    'quot_id' => $quotation->id,
                    'name' => $request->items[$key],
                    'quantity' => $request->quantities[$key],
                    'unit' => $request->units[$key],
                    'price' => $request->prices[$key],
                ]);
                $total += ($request->quantities[$key] * $request->prices[$key]);
            }
            $quotation->update([
                'cost' => $total
            ]);
            DB::commit();
            return redirect()->route('agent.quotations')->with('success','Quotation stored successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()-> with('error','An error occured');
        }
    }
    public function store(Request $request, Client $client)
    {
        $request->validate([
            'title' => ['required','regex:/^[a-zA-Z\s]+$/'],
            'details' => ['required','regex:/^[a-zA-Z0-9\s]+$/'],
            'cost' => ['required','integer']
        ]);
        // dd(Quotation::genref());
        try {
            DB::beginTransaction();
            Quotation::create([
                'reference' => Quotation::genref(),
                'client_id' => $client->id,
                'title' => $request->title,
                'cost' => $request->cost,
                'details' => $request->details
            ]);
            DB::commit();
            return redirect()->route('agent.clients')->with('success','Quotation saved successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error','Ooops. Somethings went wrong. Try again');
        }
    }

    public function invoices(){
        $user = auth()->user();
        $invoices = Quotation::from('quotations as q')
                    ->join('clients as c',function ($join) use($user){
                        $join->on('q.client_id','=','c.id')
                        ->where('c.agent_id',$user->id);
                    })
                    ->where('status','=',3)
                    ->select('q.*','c.name as client')
                    ->orderBy('q.id','DESC')
                    ->paginate(20);
        return view('invoices',compact(['invoices']));
    }
    public function show(Quotation $quotation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        $client = $quotation->client;
        $this->authorize('agentManageClient', $client);
        foreach ($quotation->sales as $key => $sale) {
            $sale->delete();
        }
        $quotation->delete();
        return back()->with('success','Quotation Erased Successfully');
    }
}
