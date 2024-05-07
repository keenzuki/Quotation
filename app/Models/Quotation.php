<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    static function genref(){
        return strtoupper('IH'.date('yW').self::gen());
    }
    protected $fillable =[
        'reference',
        'client_id',
        'title',
        'details',
        'cost',
        'status'
    ];

    private static function gen(){
        $rand = random_bytes(2);
        return bin2hex($rand);
    }
}
