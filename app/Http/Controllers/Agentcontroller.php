<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Quotation;

class AgentController extends Controller
{
    public function index(){
        $user = auth()->user();
        $p = Quotation::from('quotations as q')
                ->join('clients as c', function($join) use($user){
                    $join->on('q.client_id','c.id')
                    ->where('c.agent_id',$user->id);
                })
                ->select(
                    DB::raw("SUM(CASE WHEN q.status = 1 THEN 1 ELSE 0 END) as drafts"),
                    DB::raw("SUM(CASE WHEN q.status = 2 THEN 1 ELSE 0 END) as quotations"),
                    DB::raw("SUM(CASE WHEN q.status = 3 THEN 1 ELSE 0 END) as invoices"),
                )->first();
        return view('dashboard', compact('p'));
    }
}
