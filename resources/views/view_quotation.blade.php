@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('agent.clients') }}">Clients</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $quotation->reference }}</li>
            </ol>
        </nav>
    </div>
@endsection
