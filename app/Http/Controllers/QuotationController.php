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
        $query = Quotation::from('quotations as q')
                    ->join('clients as c', function ($join) use ($user) {
                        $join->on('q.client_id', '=', 'c.id');
                        if ($user->role_id == 3) {
                            $join->where('c.agent_id', $user->id);
                        }
                    })
                    ->when($user->role_id != 3, function($query){
                        return $query->join('users as u','u.id','=','c.agent_id');
                    })
                    ->where('q.status', '!=', 3)
                    ->when($user->role_id != 3, function($query){
                        return $query->where('q.status','!=', 1);
                    })
                    ->select('q.*', 'c.name as client')
                    ->when($user->role_id != 3, function ($query) {
                        return $query->addSelect('u.fname as rep');
                    })
                    ->orderBy('q.id', 'DESC');
        
        // Get the quotations based on the constructed query
        $quotations = $query->get();
        
        return view('quotations',compact(['quotations']));
    }
    public function quotation(Quotation $quotation)
    {
        $client = $quotation->client;
        $this->authorize('agentManageClient',$client);
        return view('view_quotation',compact(['quotation']));
    }
    // public function processInvoice(Quotation $invoice){
    //     $client = $invoice->client;
    //     dd($invoice);
    //     $this->authorize('agentManageClient', $client);
    //     try {
    //         dispatch(new PaymentProcessing($client));
    //         return back()->with('success','Processed successfully');
    //     } catch (\Throwable $th) {
    //         return back()->with('error','Sorry an error occured');
    //     }
    // }
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
            return response()->json(['success'=>'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error'=>'Error']);
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
        $staticPart = substr($quotation->reference,3); // Remove 'Q' for quotation add 'I' for Invoice
        $ref = 'IHI'.$staticPart;
        try {
            DB::beginTransaction();
            $quotation->update([
                'reference' => $ref, // Update reference
                'status' => 3 //Invoice status code
            ]);
            dispatch(new PaymentProcessing($client));
            DB::commit();
            return redirect()->route('clientprofile',['client'=>$client->id])->with('success','Quotation converted successfully');
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
            return redirect()->route('clientprofile',['client'=>$client->id])->with('success','Quotation updated successfully');
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
            return redirect()->route('clientprofile',['client'=>$client->id])->with('success','Draft saved as Quotation successfully');
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
            return redirect()->route('clientprofile',['client'=>$client->id])->with('success','Quotation stored successfully');
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
            return redirect()->route('clientprofile',['client'=>$client->id])->with('success','Quotation saved successfully');
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
                        ->when($user->role_id == 3, function($query)use($user){
                           return $query->where('c.agent_id',$user->id);
                        });  
                    })
                    ->when($user->role_id != 3, function($query){
                        return $query->join('users as u','u.id','=','c.agent_id');
                    })
                    ->select('q.*','c.name as client')
                    ->when($user->role_id != 3, function ($query) {
                        return $query->addSelect('u.fname as rep');
                    })
                    ->where('q.status','=',3)
                    ->orderBy('q.id','DESC')
                    ->get();
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
        if ($invoice->paymentAllocations()->count() > 0) {
            return back()->with('error','This invoice has payments already');
        }
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
            return redirect()->route('clientprofile',['client'=>$client->id])->with('success','Invoice updated successfully');
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
    public function destroyInvoice(Quotation $invoice)
    {
        $client = $invoice->client;
        $this->authorize('agentManageClient', $client);
        // dd($invoice->paymentAllocations()->count());
        if($invoice->paymentAllocations()->count()>0){
            return back()->with('error','Can\'t delete invoice as it has been paid for. Contact admin 👋');
        }
        foreach ($invoice->sales as $key => $sale) {
            $sale->delete();
        }
        $invoice->delete();
        return back()->with('success','Invoice Erased Successfully');
    }

    public function invoicePdf(Quotation $invoice)
    { 
        $client = $invoice->client;
        $this->authorize('agentManageClient', $client);
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
                    'p.sys_ref',
                    'p.mode',
                    'p.paid_on as date',
                    'pa.allocated as amount'
                )
                ->get();
        $title = "customer invoice";
        $type = "Invoice";
        $date = $invoice->created_at;
        $reference ='Ref: '. $invoice->reference;
        $project_title = $invoice->title;
        $pdf = PDF::loadView('reports.invoice_pdf',compact(['invoice','client','sales','title','payments','type','date','reference','project_title']));
        $pdf->setPaper("a4", "portrait");
        $pdfname= 'invoice-'.$invoice->reference.'.pdf';
        $doc = $pdf->stream($pdfname);
        return $doc;
    }
    public function quotationPdf(Quotation $quotation)
    { 
        $client = $quotation->client;
        $this->authorize('agentManageClient', $client);
        $sales = Sales::where('quot_id',$quotation->id)->get();
        $client= $quotation->client;
        $title = "quotation";
        $type = "Quotation";
        $date = $quotation->created_at;
        $reference = 'Ref: '.$quotation->reference;
        $project_title = $quotation->title;
        $pdf = PDF::loadView('reports.quotation_pdf',compact(['quotation','client','sales','title','type','date','reference','project_title']));
        $pdf->setPaper("a4", "portrait");
        $pdfname= 'quotation-'.$quotation->reference.'.pdf';
        $doc = $pdf->stream($pdfname);
        return $doc;
    }
}
