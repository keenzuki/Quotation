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
            <div class="card-header d-flex justify-content-between">
                <h4>My Clients Quotations
                    <a class="btn-primary bg-primary rounded-circle" href="{{ route('agent.makequotation') }}"><i
                            class="bi bi-plus"></i></a>
                </h4>
                <div>
                    <input type="text" class="form-control" placeholder="Search ...">
                </div>
            </div>
            <div class="card-body">
                @include('quotations_table')
                <div class="row">
                    <div class="col-12 text-center">
                        {{ $quotations->links() }}
                    </div>
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
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>
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
            // Add Row button click event
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
