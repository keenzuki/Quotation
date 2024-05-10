@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('agent.quotations') }}">Quotations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quotation - <span
                        class="bg-secondary px-1 rounded text-white">{{ $quotation->reference }}</span></li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                Edit Quotation
            </div>
            <div class="card-body">
                <form id="salesForm" action="{{ route('agent.updatequotation', ['quotation' => $quotation->id]) }}"
                    method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <label for="client">Client</label>
                            <select required name="client" id="client" class="form-select">
                                <option value="client" selected>{{ $quotation->client->name }}</option>
                                {{-- @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="title">Title</label>
                            <input value="{{ $quotation->title }}" type="text" class="form-control text-capitalize"
                                name="title" id="title" required>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Item&nbsp;Name</th>
                                    <th>Price&nbsp;(Ksh)</th>
                                    <th>Quantity</th>
                                    <th>Unit&nbsp;Measure</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                @foreach ($quotation->sales as $sale)
                                    <tr>
                                        <td><input value="{{ $sale->name }}" type="text" name="items[]"
                                                autocomplete="off" class="form-control text-capitalize"></td>
                                        <td><input value="{{ $sale->price }}" type="number" name="prices[]"
                                                class="form-control"></td>
                                        <td><input value="{{ $sale->quantity }}" type="number" name="quantities[]"
                                                class="form-control"></td>
                                        <td><input value="{{ $sale->unit }}" type="text" name="units[]"
                                                class="form-control text-capitalize"></td>
                                        <td><input value="{{ $sale->quantity * $sale->price }}" type="text"
                                                name="subtotals[]" class="form-control" readonly>
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <h1 class="fs-5">Total</h1>
                                </td>
                                <td>
                                    <h1 class="fs-5" id="QTotal">{{ $quotation->cost }}</h1>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="addRow">Add Row</button>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <textarea required name="comments" id="comments" rows="2" class="form-control">{{ $quotation->details }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                    {{-- <button type="button" class="btn btn-info mt-3" id="saveDraftBtn">Save Draft</button> --}}
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                // Add Row button click event
                $('#addRow').click(function() {
                    $('#itemsTableBody').append(
                        '<tr>' +
                        '<td><input required autocomplete="off" type="text" name="items[]" class="form-control text-capitalize"></td>' +
                        '<td><input required autocomplete="off" type="number" name="prices[]" class="form-control"></td>' +
                        '<td><input required autocomplete="off" type="number" name="quantities[]" class="form-control"></td>' +
                        '<td><input required autocomplete="off" type="text" name="units[]" class="form-control text-capitalize"></td>' +
                        '<td><input autocomplete="off" type="text" name="subtotals[]" class="form-control" readonly></td>' +
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
                    $('#QTotal').text(total.toFixed(2));
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

                $('#saveDraftBtn').click(function() {
                    // Function to save draft
                    var formData = $('#salesForm').serialize(); // Serialize form data
                    $.ajax({
                        url: "{{ route('agent.savedraft') }}", // Route to save draft action
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            // Handle success response, e.g., show a success message
                            console.log(response);
                            alert('Draft saved successfully.');
                        },
                        error: function(xhr, status, error) {
                            // Handle error response, e.g., show an error message
                            console.error(xhr.responseText);
                            alert('An error occurred while saving draft.');
                        }
                    });
                });
            });
        </script>

    </div>
@endsection
