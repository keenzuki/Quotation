<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\PaymentProcessing;

class PaymentController extends Controller
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
    public function create()
    {
        //
    }

    public function store(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'mode' => 'required',
            'amount' => 'required|min:1',
            'date' => 'required|date',
            'reference' => 'required_if:mode,mpesa,cheque,bank_deposit',
            'bank_name' => 'required_if:mode,bank_deposit,cheque',
            'account' => 'required_if:mode,bank_deposit,cheque,mpesa',
        ]);
        // try {
            DB::beginTransaction();
            $payment = Payment::create([
                'mode' => $validatedData['mode'],
                'amount' => $validatedData['amount'],
                'date' => $validatedData['date'],
                'reference' => $validatedData['reference'] ?? null,
                'bank' => $validatedData['bank_name'] ?? null,
                'account' => $validatedData['account'] ?? null,
                'paid_on' => $validatedData['date'],
                'client_id' => $client->id
            ]);
            DB::commit();
            $client = $payment->client;
            dispatch(new PaymentProcessing($client));
            return redirect()->back()->with('success', 'Payment saved successfully!');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->back()->with('error', 'Failed to save payment');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
