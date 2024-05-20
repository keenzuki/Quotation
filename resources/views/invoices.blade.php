@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Invoices</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Clients' Invoices
                    {{-- <button type="button" class="btn-primary bg-primary rounded-circle" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        <i class="bi bi-plus"></i>
                    </button> --}}
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="invoicesTable"
                        class="table table-striped table-hover table-borderless table-primary align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Ref</th>
                                @can('seeAdminCont', auth()->user())
                                    <th>Rep</th>
                                @endcan
                                <th>Title</th>
                                <th>Client</th>
                                <th>Price (Ksh)</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($invoices as $key => $invoice)
                                <tr class="table-primary">
                                    <td scope="row">
                                        {{ $key + 1 }}
                                    </td>
                                    <td>{{ $invoice->reference }} </td>
                                    @can('seeAdminCont', auth()->user())
                                        <td>{{ $invoice->rep }} </td>
                                    @endcan
                                    <td>{{ $invoice->title }}</td>
                                    <td>{{ $invoice->client }}</td>
                                    <td>{{ number_format($invoice->cost, 2, '.', ',') }}</td>
                                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="dropdown open">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                invoice
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                                <a class="dropdown-item"
                                                    href="{{ route('viewinvoice', ['invoice' => $invoice->id]) }}"><i
                                                        class="bi bi-tv text-secondary"></i> View Invoice</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('editinvoice', ['invoice' => $invoice->id]) }}"><i
                                                        class="bi bi-pencil-square text-info"></i> Edit
                                                    invoice</a>
                                                {{-- <a class="dropdown-item"
                                                    href="{{ route('agent.editinvoice', ['invoice' => $invoice->id]) }}"><i
                                                        class="bi bi-cash-coin text-primary"></i> Make Payment</a> --}}
                                                <hr>
                                                <form class="dropdown-item"
                                                    action="{{ route('agent.deleteinvoice', ['invoice' => $invoice->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-danger" type="submit"
                                                        onclick="return confirm('Please Confrrm you want to delete this Invoice')"><i
                                                            class="bi bi-trash text-danger"></i>
                                                        Delete</button>
                                                </form>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="7" class="text-center text-info">No data available</td>
                            @endforelse
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Client Registration</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('agent.registerclient') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="name">Full name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="col-6">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control" id="phone">
                            </div>
                            <div class="col-6">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" id="email">
                            </div>
                            <div class="col-6">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <script>
        $(document).ready(function() {
            $('#invoicesTable').DataTable();
        });
    </script>
@endsection
