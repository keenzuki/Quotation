<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\PaymentProcessing;
use Illuminate\Support\Facades\Validator;
use PDF;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $payments = Payment::from('payments as p')
                ->join('clients as c', function($join) use($user){
                    $join->on('c.id','p.client_id')
                    ->when($user->role_id == 3, function($query) use($user){
                        return $query->where('c.agent_id',$user->id);
                    });
                })
                ->when($user->role_id != 3, function($query){
                    return $query->join('users as u','u.id','=','c.agent_id');
                })
                ->select('p.id','p.sys_ref','c.name as client','p.amount','paid_on as date','p.status','p.mode',
                    DB::raw("COALESCE(p.reference, 'N/A') as reference")
                )
                ->when($user->role_id != 3, function ($query) {
                    return $query->addSelect('u.fname as rep');
                })
                ->orderBy('p.paid_on','DESC')
                ->paginate(20);
        return view('payments', compact('payments'));
    }

    public function processPayment(Payment $payment){
        $client = $payment->client;
        $this->authorize('agentManageClient', $client);
        try {
            DB::beginTransaction();
            $payment->update([
                'processed' => true
            ]);
            DB::commit();
            dispatch(new PaymentProcessing($client));
            return back()->with('success','Processed successfully');
        } catch (\Throwable $th) {
            return back()->with('error','Sorry an error occured');
        }
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
        // dd(Payment::genref($validatedData['mode']));
        try {
            DB::beginTransaction();
            $payment = Payment::create([
                'sys_ref' => Payment::genref(),
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
            // $client = $payment->client;
            // dispatch(new PaymentProcessing($client));
            return redirect()->back()->with('success', 'Payment saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save payment');
        }
    }

    public function show(Payment $payment)
    {
        $user = auth()->user();
        $payment = Payment::from('payments as p')
                ->join('clients as c', function($join) use($user){
                    $join->on('c.id','p.client_id')
                    ->when($user->role_id == 3, function($query) use($user){
                        return $query->where('c.agent_id',$user->id);
                    });
                })
                ->select('p.id','p.sys_ref','c.name as client','p.amount','paid_on as date','p.mode',
                    DB::raw("COALESCE(p.reference, 'N/A') as reference"),
                    DB::raw("COALESCE(p.bank, 'N/A') as bank_name"),
                    DB::raw("COALESCE(p.account, 'N/A') as account_no"),
                    DB::raw("CASE 
                            WHEN p.status = 1 THEN 'Unallocated'
                            WHEN p.status = 2 THEN 'Partially Allocated'
                            WHEN p.status = 3 THEN 'Fully Allocated'
                            ELSE 'Unknown' END as status"
                            )
                )
                ->where('p.id',$payment->id)
                ->first();
        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'mode' => 'required',
            'amount' => 'required|min:1',
            // 'date' => 'required|date',
            'reference' => 'required_if:mode,mpesa,cheque,bank_deposit',
            'bank_name' => 'required_if:mode,bank_deposit,cheque',
            'account_no' => 'required_if:mode,bank_deposit,cheque,mpesa',
        ]);
        if ($payment->processed) {
            return response()->json([
                'success' => false,
                'errors' => 'This payment has already been processed hence can\'t be edited'
            ]);
        }
            if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        }
        try {
            $payment->update([
                'amount' => $request->amount,
                // 'date' => $request->date,
                'reference' => $payment->mode == 'cash' ? null: $request->reference,
                'bank' => $request->bank_name,
                'account' => $request->account_no,
                // 'paid_on' => $request->date,
            ]);
    
            // Return success message as JSON
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Return error message as JSON
            return response()->json(['success' => false, 'errors' => ['Failed to update payment'.$e]]);
        }
    }

    public function paymentPdf(Payment $payment)
    { 
        $client = $payment->client;
        $this->authorize('agentManageClient', $client);
        // $sales = Sales::where('quot_id',$quotation->id)->get();
        // $client= $quotation->client;
        $title = "Payment Confirmation";
        $type = "Payment";
        $date = $payment->created_at;
        $reference ='Reciept No: '. $payment->sys_ref;
        // $project_title = $quotation->title;
        $pdf = PDF::loadView('reports.payment_pdf',compact(['payment','client','title','type','date','reference']));
        $pdf->setPaper("a4", "portrait");
        $pdfname= 'payment-'.$reference.'.pdf';
        $doc = $pdf->stream($pdfname);
        return $doc;
    }
}
