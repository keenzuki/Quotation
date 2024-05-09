<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'quot_id',
        'name',
        'quantity',
        'unit',
        'price'
    ];
    public function quotation(){
        return $this->belongsTo(Quotation::class,'quot_id','id');
    }
}
