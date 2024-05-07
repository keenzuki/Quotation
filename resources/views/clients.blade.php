@extends('layouts.app')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dash</a></li>
            <li class="breadcrumb-item active" aria-current="page">Clients</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header">
            My Clients
        </div>
        <div class="card-body">
            @include('clients_table')
        </div>
    </div>
@endsection
