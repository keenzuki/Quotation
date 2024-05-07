<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client)
    {
        return view('create_quotation',compact(['client']));
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
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
        //
    }
}
