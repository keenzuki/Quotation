<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sales::from('sales as s')
            ->join('quotations as q', function($join){
                $join->on('q.id','s.quot_id')
                ->join('clients as c', function($join){
                    $join->on('c.id','q.client_id')
                    ->join('users as u','u.id','=','c.agent_id');
                });
            })
            ->select(
                'u.fname as agent',
                's.name as item',
                's.price',
                's.unit',
                's.created_at as time'
            )->paginate(10);
            return view('items',compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
