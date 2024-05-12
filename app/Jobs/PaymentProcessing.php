<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\Client;
use App\Models\Quotation;

class PaymentProcessing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $client;
    public function __construct($client)
    {
        $this->client= $client;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payments = Payment::where('client_id', $this->client->id)
                    ->where('status','!=',3)
                    ->orderBy('id','ASC')   
                    ->get();
        foreach ($payments as $payment) {
            $balance = $this->diff($payment->amount,$payment->allocated);
            $invoices = Quotation::where('client_id',$this->client->id)
                        ->where('pay_status','!=',3) // ensure its not fully paid
                        ->where('status','=',3) // Ensure its an invoice
                        ->orderBy('id','ASC')
                        ->get();
            // dd($invoices);
            foreach ($invoices as $invoice) {
                $unpaid = $this->diff($invoice->cost,$invoice->paid_amount);
                if ($unpaid <= $balance) {
                    $invoice->update([
                        'paid_amount'=>$invoice->paid_amount + $unpaid,
                        'pay_status' => 3
                    ]);
                    PaymentAllocation::create([
                        'inv_id' => $invoice->id,
                        'pay_id' => $payment->id,
                        'allocated' => $unpaid
                    ]);
                    $balance -= $unpaid;
                    if ($balance == 0) {
                        $payment->update([
                            'allocated'=>$payment->allocated + $unpaid,
                            'status'=>3
                        ]);
                        break;
                    }
                    $payment->update([
                        'allocated'=> $payment->allocated + $unpaid,
                        'status'=>2
                    ]);
                }else {
                    $invoice->update([
                        'paid_amount' => $invoice->paid_amount + $balance,
                        'pay_status'=> 2
                    ]);
                    $payment->update([
                        'allocated' => $payment->amount,
                        'status'=>3
                    ]);
                    PaymentAllocation::create([
                        'inv_id' => $invoice->id,
                        'pay_id' => $payment->id,
                        'allocated' => $balance
                    ]);
                    break;
                }
            }
        }
    }

    private function diff($a,$b){
        return ($a-$b);
    }
}
