<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'phone',
        'email',
        'address',
        'agent_id'
    ];
    public function agent(){
        return $this->belongsTo(User::class,'agent_id','id');
    }
}
