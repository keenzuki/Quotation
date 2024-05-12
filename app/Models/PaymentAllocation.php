<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    use HasFactory;
    protected $fillable =[
        'inv_id',
        'pay_id',
        'allocated'
    ];
}
