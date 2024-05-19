@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('agent.invoices') }}">Invoices</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $invoice->reference }}</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header text-center">
                Client Details
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-primary align-middle">
                        <thead class="table-light">
                            {{-- <caption class="caption-top">
                                Client Details
                            </caption> --}}
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr class="table-primary">
                                <td scope="row">{{ $invoice->client->name }}</td>
                                <td>{{ $invoice->client->phone }}</td>
                                <td>{{ $invoice->client->email }}</td>
                                <td>{{ $invoice->client->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed table-primary align-middle">
                        <thead class="table-light">
                            <caption class="caption-top text-center">
                                <h3 class="fs-5 m-0">{{ $invoice->reference }}</h3>
                            </caption>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php
                            $total = 0;
                            ?>
                            @foreach ($sales as $sale)
                                <tr class="table-primary">
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $sale->name }}</td>
                                    <td>{{ number_format($sale->price, 2, '.', ',') }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>{{ $sale->unit }}</td>
                                    <td>{{ number_format($sale->price * $sale->quantity, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <?php
                                $total += $sale->price * $sale->quantity;
                                ?>
                            @endforeach
                            <td colspan="5">
                                <h1 class="fs-5 text-center">TOTAL</h1>
                            </td>
                            <td>
                                <h1 class="fs-5">Ksh&nbsp;{{ number_format($total, 2, '.', ',') }}</h1>
                            </td>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-around py-2">
                    {{-- <button class="btn btn-primary">Print</button> --}}
                    <button class="btn btn-primary"><i class="bi bi-envelope-at mr-1"></i> Email</button>
                    <a href="{{ route('invoicepdf', ['invoice' => $invoice->id]) }}" class="btn btn-success"><i
                            class="bi bi-filetype-pdf mr-1"></i>PDF</a>
                </div>
            </div>
        </div>
    </div>
@endsection
