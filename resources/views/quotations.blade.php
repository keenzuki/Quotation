@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quotations</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                <h4>Clients' Quotations
                    @cannot('seeAdminCont', auth()->user())
                        <a class="btn-primary bg-primary rounded-circle" href="{{ route('agent.makequotation') }}"><i
                                class="bi bi-plus"></i></a>
                    @endcannot
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="quotationsTable"
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
                            @forelse ($quotations as $key => $quotation)
                                <tr class="table-primary">
                                    <td scope="row">{{ $key + 1 }}
                                    </td>
                                    <td>{{ $quotation->reference }} </td>
                                    @can('seeAdminCont', auth()->user())
                                        <td>{{ $quotation->rep }} </td>
                                    @endcan
                                    <td>{{ $quotation->title }}</td>
                                    <td>{{ $quotation->client }}</td>
                                    <td>{{ number_format($quotation->cost, 2, '.', ',') }}</td>
                                    <td>{{ $quotation->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="dropdown open">

                                            @if ($quotation->status == 1)
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                    id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Draft
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                                    <a class="dropdown-item text-primary"
                                                        href="{{ route('agent.editdraft', ['quotation' => $quotation->id]) }}"><i
                                                            class="bi bi-check-circle text-primary"></i> Complete</a>
                                                    <hr>
                                                    <form class="dropdown-item"
                                                        action="{{ route('agent.deletequotation', ['quotation' => $quotation->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="text-danger" type="submit"
                                                            onclick="return confirm('Please Confrrm you want to delete the draft')"><i
                                                                class="bi bi-trash text-danger"></i>
                                                            Delete</button>
                                                    </form>
                                                </div>
                                            @elseif ($quotation->status == 2)
                                                <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                                    id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Quotation
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                                    @can('seeAdminCont', auth()->user())
                                                        <a class="dropdown-item"
                                                            href="{{ route('quotationpdf', ['quotation' => $quotation->id]) }}"><i
                                                                class="fs-5 bi bi-eye text-success"></i> View
                                                            Quotation</a>
                                                    @else
                                                        <a class="dropdown-item text-success"
                                                            href="{{ route('agent.updateQ2I', ['quotation' => $quotation->id]) }}"
                                                            onclick="return confirm('Do you really want to convert this Quotation to Invoice direct?')"><i
                                                                class="bi bi-pen text-success"></i> Make
                                                            Invoice</a>
                                                        <a class="dropdown-item text-info"
                                                            href="{{ route('agent.editquotation', ['quotation' => $quotation->id]) }}"><i
                                                                class="bi bi-pencil-square text-info"></i> Edit
                                                            Quotation</a>
                                                        <hr>
                                                        <form class="dropdown-item"
                                                            action="{{ route('agent.deletequotation', ['quotation' => $quotation->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="text-danger" type="submit"
                                                                onclick="return confirm('Please Confrrm you want to delete the draft')"><i
                                                                    class="bi bi-trash text-danger"></i>
                                                                Delete</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            @endif

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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Make Quotation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="salesForm" action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="client">Client</label>
                                <select name="client" id="client" class="form-select">
                                    <option value="">Client</option>
                                </select>
                            </div>
                        </div>
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <tr>
                                    <td><input type="text" name="items[]" class="form-control"></td>
                                    <td><input type="number" name="quantities[]" class="form-control"></td>
                                    <td><input type="number" name="prices[]" class="form-control"></td>
                                    <td><input type="text" name="subtotals[]" class="form-control" readonly></td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success btn-sm" id="addRow">Add Row</button>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <label for="total">Total</label>
                                <input type="text" name="total" id="total" class="form-control" readonly>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                        <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#quotationsTable').DataTable();
            $('#addRow').click(function() {
                $('#itemsTableBody').append(
                    '<tr>' +
                    '<td><input type="text" name="items[]" class="form-control"></td>' +
                    '<td><input type="number" name="quantities[]" class="form-control"></td>' +
                    '<td><input type="number" name="prices[]" class="form-control"></td>' +
                    '<td><input type="text" name="subtotals[]" class="form-control" readonly></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>' +
                    '</tr>'
                );
            });

            // Remove Row button click event
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
                calculateTotal();
            });

            // Calculate total
            function calculateTotal() {
                var total = 0;
                $('input[name="subtotals[]"]').each(function() {
                    var subtotal = parseFloat($(this).val());
                    if (!isNaN(subtotal)) {
                        total += subtotal;
                    }
                });
                $('#total').val(total.toFixed(2));
            }

            // Calculate subtotal when quantity or price changes
            $(document).on('input', 'input[name="quantities[]"], input[name="prices[]"]', function() {
                var row = $(this).closest('tr');
                var quantity = parseFloat(row.find('input[name="quantities[]"]').val());
                var price = parseFloat(row.find('input[name="prices[]"]').val());
                var subtotal = quantity * price;
                if (!isNaN(subtotal)) {
                    row.find('input[name="subtotals[]"]').val(subtotal.toFixed(2));
                    calculateTotal();
                }
            });
        });
    </script>
@endsection
