@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                Sales Knowledge Base
            </div>
            <div class="card-body p-1">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-primary align-middle">
                        <thead class="table-light">
                            <caption class="caption-top">
                                Selling database
                            </caption>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Unit</th>
                                <th>Selling Date</th>
                                <th>Rep</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($sales as $sale)
                                <tr class="table-primary">
                                    <td>{{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}</td>
                                    <td>{{ $sale->item }}</td>
                                    <td>{{ $sale->price }}</td>
                                    <td>{{ $sale->unit }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sale->time)->format('D d M, Y') }}</td>
                                    <td>{{ $sale->agent }}</td>
                                </tr>
                            @empty
                                <td colspan="6" class="text-center">No data available</td>
                            @endforelse
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
