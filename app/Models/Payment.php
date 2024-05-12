<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
        'status'
    ];
    public function client(){
        return $this->belongsTo(Client::class,'client_id','id');
    }
}
