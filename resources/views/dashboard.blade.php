@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col">
                <a title="pending Drafts" href="{{ route('agent.quotations') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-header">
                            Drafts
                        </div>
                        <div class="card-body">
                            <p class="fs-3"><i class="bi bi-envelope px-2"></i> {{ $p->drafts }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a title="pending Quotations" href="{{ route('agent.quotations') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-header">
                            Quotations
                        </div>
                        <div class="card-body">
                            <p class="fs-3"><i class="bi bi-envelope px-2"></i> {{ $p->quotations }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a title="All Invoices" href="{{ route('agent.invoices') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-header">
                            Invoices
                        </div>
                        <div class="card-body">
                            <p class="fs-3"><i class="bi bi-envelope px-2"></i> {{ $p->invoices }}</p>
                        </div>
                    </div>
                </a>
            </div>
            {{-- <div class="col">
                <div class="card">
                    <div class="card-header">
                        Quotations
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
