<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    static function genref(){
        $prefix = date('yW');
        // Find the latest payment based on the payment method
        $latestpayment = static::where('sys_ref', 'like', $prefix . '%')->latest('id')->first();
        if (!$latestpayment) {
            // If there are no existing payments
            return $prefix . '001';
        }
        // return $prefix;
    
        $currentId = $latestpayment->sys_ref;
    
        $numericPart = (int) substr($currentId, 4);
    
        // Increment the numeric part and reset to 1 if it reaches 9999
        $numericPart++;
    
        // Format the new ID by concatenating the letter part with the formatted numeric part
        $newId = $prefix . sprintf('%03d', $numericPart);
    
        return $newId;
    }
    use HasFactory;
    protected $fillable = [
        'reference',
        'amount',
        'allocated',
        'mode',
        'bank',
        'account',
        'client_id',
        'paid_on',
        'status',
        'sys_ref',
        'processed'
    ];
    public function client(){
        return $this->belongsTo(Client::class,'client_id','id');
    }
}
