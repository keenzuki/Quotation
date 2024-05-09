@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('agent.clients') }}">Clients</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                {{ $client->name }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-primary align-middle">
                        <thead class="table-light">
                            <caption class="caption-top">
                                Client Details
                            </caption>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr class="table-primary">
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-primary align-middle">
                        <thead class="table-light">
                            <caption class="caption-top">
                                Projects & Activities
                            </caption>
                            <tr>
                                <th>#</th>
                                <th>Ref</th>
                                <th>Project/Activity</th>
                                <th>Details</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($quotations as $quotation)
                                <tr class="table-primary">
                                    <td>{{ ($quotations->currentPage() - 1) * $quotations->perPage() + $loop->iteration }}
                                    </td>
                                    <td><a
                                            href="{{ route('agent.viewquotation', ['quotation' => $quotation->id]) }}">{{ $quotation->reference }}</a>
                                    </td>
                                    <td>{{ $quotation->title }}</td>
                                    <td>{!! nl2br(e($quotation->details)) !!}</td>
                                    <td>{{ $quotation->cost }}</td>
                                    <td>
                                        @if ($quotation->status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($quotation->status == 1)
                                            <span class="badge bg-info">Approved</span>
                                        @elseif ($quotation->status == 2)
                                            <span class="badge bg-primary">In-Progress</span>
                                        @elseif ($quotation->status == 3)
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>{{ $quotation->created_at }}</td>
                                </tr>
                            @empty
                                <td colspan="4" class="text-info">No data available</td>
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
