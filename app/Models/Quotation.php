<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    static function genref(){
        return strtoupper('IHQ'.date('yW').self::gen());
    }
    protected $fillable =[
        'reference',
        'client_id',
        'title',
        'details',
        'cost',
        'status',
        'pay_status',
        'paid_amount'
    ];

    public function client(){
        return $this->belongsTo(Client::class,'client_id','id');
    }
    public function sales(){
        return $this->hasMany(Sales::class,'quot_id','id');
    }
    public function paymentAllocations(){
        return $this->hasMany(PaymentAllocation::class,'inv_id','id');
    }

    private static function gen(){
        $rand = random_bytes(2);
        return bin2hex($rand);
    }
}
