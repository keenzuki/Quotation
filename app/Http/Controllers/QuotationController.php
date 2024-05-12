<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\PaymentProcessing;
use PDF;

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
    public function create(Client $client)
    {
        return view('create_quotation',compact(['client']));
    }
    public function makeQuotation()
    {
        $clients=Client::where('agent_id',auth()->user()->id)->get();
        return view('make_quotation',compact(['clients']));
    }
    public function addQuotation(Client $client)
    {
        $this->authorize('agentManageClient', $client);
        return view('add_quotation',compact(['client']));
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
        try {
            DB::beginTransaction();
            $quotation->update([
                'status' => 3 //Invoice status code
            ]);
            dispatch(new PaymentProcessing($client));
            DB::commit();
            return redirect()->route('agent.clientprofile',['client'=>$client->id])->with('success','Quotation converted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error','An error occured. Try again');
        }
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
                // 'status' => 2,
                'details' => $request->comments,
                'cost' => $total
            ]);
            DB::commit();
            // if($request->route()->getName()=="agent.update"){
            // };
            return redirect()->route('agent.clientprofile',['client'=>$client->id])->with('success','Quotation updated successfully');
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
            return redirect()->route('agent.clientprofile',['client'=>$client->id])->with('success','Draft saved as Quotation successfully');
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
        $client = Client::where('id',$request->client)->first();
        if (!$this->authorize('agentManageClient',$client)) {
            return back()->with('error','You don\'t have enough previledge for this client');
        }
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
            return redirect()->route('agent.clientprofile',['client'=>$client->id])->with('success','Quotation stored successfully');
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
            return redirect()->route('agent.clientprofile',['client'=>$client->id])->with('success','Quotation saved successfully');
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
    public function invoice(Quotation $invoice)
    {
        $client = $invoice->client;
        $this->authorize('agentManageClient',$client);
        $sales = Sales::where('quot_id',$invoice->id)->get();
        return view('view_invoice',compact(['invoice','sales']));
    }
    public function editInvoice(Quotation $invoice){
        $client = $invoice->client;
        $this->authorize('agentManageClient', $client);
        if ($invoice->paymentAllocations()->count() > 0) {
            return back()->with('error','This invoice has payments already');
        }
        return view('edit_invoice',compact(['invoice']));
    }
    public function updateInvoice(Request $request,Quotation $invoice){
        // dd($request);
        $request->validate([
            'items.*' => 'required',
            'quantities.*' => 'required|min:1',
            'units.*' => 'required',
            'prices.*' => 'required|min:0',
            'comments' => 'required'
        ]);
        $client=$invoice->client;
        $this->authorize('agentManageClient', $client);
        try {
            DB::beginTransaction();
            foreach ($invoice->sales as $key => $item) {
                $item->delete();
            }
            $total = 0;
            foreach ($request->items as $key => $item) {
                Sales::create([
                    'quot_id' => $invoice->id,
                    'name' => $request->items[$key],
                    'quantity' => $request->quantities[$key],
                    'unit' => $request->units[$key],
                    'price' => $request->prices[$key],
                ]);
                $total += ($request->quantities[$key] * $request->prices[$key]);
            }
            $invoice->update([
                'title' =>$request->title,
                // 'status' => 2,
                'details' => $request->comments,
                'cost' => $total
            ]);
            DB::commit();
            // if($request->route()->getName()=="agent.update"){
            // };
            return redirect()->route('agent.clientprofile',['client'=>$client->id])->with('success','Invoice updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()-> with('error','An error occured');
        }
    }
    public function destroy(Quotation $quotation)
    {
        $client = $quotation->client;
        $this->authorize('agentManageClient', $client);
        $status = $quotation->status;
        if ($status == 1) {
            $category = "Draft";
        } elseif ($status == 2) {
            $category = "Quotation";
        }elseif ($status == 3) {
            $category = "Invoice";
        }
        foreach ($quotation->sales as $key => $sale) {
            $sale->delete();
        }
        $quotation->delete();
        return back()->with('success',$category.' Erased Successfully');
    }


    // public function invoicePdf(Quotation $invoice)
    // {
    //     $sales = Sales::where('quot_id',$invoice->id)->get();
    //     $data = ['invoice' => $invoice,'sales'=>$sales];
    //     $pdf = PDF::loadView('reports.invoice_pdf', compact('invoice','sales'));
    //     return $pdf->download('document.pdf');
    // }
    public function invoicePdf(Quotation $invoice)
    { 
        $sales = Sales::where('quot_id',$invoice->id)->get();
        // $data = ['invoice' => $invoice,'sales'=>$sales];
        $client= $invoice->client;
        $payments = Payment::from('payments as p')
                ->join('payment_allocations as pa', function($join) use($invoice){
                    $join->on('pa.pay_id','p.id')
                    ->where('pa.inv_id',$invoice->id);
                })
                ->where('client_id',$client->id)
                ->select(
                    DB::raw("COALESCE(p.reference , 'N/A') as reference"),
                    'p.mode',
                    'p.paid_on as date',
                    'pa.allocated as amount'
                )
                ->get();
        $title = "customer invoice";
        $pdf = PDF::loadView('reports.invoice_pdf',compact(['invoice','client','sales','title','payments']));
        $pdf->setPaper("a4", "portrait");
        $pdfname= 'invoice-'.$invoice->reference.'.pdf';
        $doc = $pdf->stream($pdfname);
        return $doc;
    }
    public function quotationPdf(Quotation $quotation)
    { 
        $sales = Sales::where('quot_id',$quotation->id)->get();
        // $data = ['invoice' => $quotation,'sales'=>$sales];
        $client= $quotation->client;
        // $payments = Payment::from('payments as p')
        //         ->join('payment_allocations as pa', function($join) use($quotation){
        //             $join->on('pa.pay_id','p.id')
        //             ->where('pa.inv_id',$quotation->id);
        //         })
        //         ->where('client_id',$client->id)
        //         ->select(
        //             DB::raw("COALESCE(p.reference , 'N/A') as reference"),
        //             'p.mode',
        //             'p.paid_on as date',
        //             'pa.allocated as amount'
        //         )
        //         ->get();
        $title = "customer invoice";
        $pdf = PDF::loadView('reports.quotation_pdf',compact(['quotation','client','sales','title']));
        $pdf->setPaper("a4", "portrait");
        $pdfname= 'invoice-'.$quotation->reference.'.pdf';
        $doc = $pdf->stream($pdfname);
        return $doc;
    }
}
