@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('agent.quotations') }}">Quotations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Make</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                Make Quotation
            </div>
            <div class="card-body">
                <form id="salesForm" action="{{ route('agent.storequotation') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <label for="client">Client</label>
                            <select required name="client" id="client" class="form-select">
                                <option value="" selected disabled>Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="title">Title</label>
                            <input type="text" class="form-control text-capitalize" name="title" id="title"
                                required>
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
                                <tr>
                                    <td><input required type="text" name="items[]" autocomplete="off"
                                            class="form-control text-capitalize"></td>
                                    <td><input required type="number" name="prices[]" class="form-control"></td>
                                    <td><input required type="number" name="quantities[]" class="form-control"></td>
                                    <td><input required type="text" name="units[]" class="form-control text-capitalize">
                                    </td>
                                    <td><input type="text" name="subtotals[]" class="form-control" readonly>
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <h1 class="fs-5">Total</h1>
                                </td>
                                <td>
                                    <h1 class="fs-5" id="QTotal"></h1>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="addRow">Add Row</button>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <textarea required name="comments" id="comments" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        <button type="button" class="btn btn-info mt-3" id="saveDraftBtn">
                            Save Draft
                            <span id="loading" class="spinner-border spinner-border-sm text-dark d-none"
                                role="status"></span>
                        </button>
                    </div>
                    <div class="text-center">
                        <p class="animate__animated" id="message"></p>
                    </div>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                // Add Row button click event
                $('#addRow').click(function() {
                    $('#itemsTableBody').append(
                        '<tr>' +
                        '<td><input autocomplete="off" type="text" name="items[]" class="form-control text-capitalize"></td>' +
                        '<td><input autocomplete="off" type="number" name="prices[]" class="form-control"></td>' +
                        '<td><input autocomplete="off" type="number" name="quantities[]" class="form-control"></td>' +
                        '<td><input autocomplete="off" type="text" name="units[]" class="form-control text-capitalize"></td>' +
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
                    $('#loading').removeClass('d-none');
                    var formData = $('#salesForm').serialize();
                    $.ajax({
                        url: "{{ route('agent.savedraft') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            $('#loading').addClass('d-none');
                            actionMessage('Draft Saved successfully', 'text-success', true);
                        },
                        error: function(xhr, status, error) {
                            actionMessage('Sorry an error occurred', 'text-danger', false);
                            $('#loading').addClass('d-none');
                        }
                    });
                });

                function actionMessage(message, colorClass, draftSaved) {
                    $('#message').removeClass('animate__fadeOut').addClass('animate__fadeIn').text(message).removeClass(
                        'text-success text-danger').addClass(colorClass).show();
                    setTimeout(function() {
                        $('#message').removeClass('animate__fadeIn').addClass('animate__fadeOut');
                        if (draftSaved) {
                            window.location.href = "{{ route('agent.quotations') }}";
                        }
                    }, 4000);
                }





            });
        </script>

    </div>
@endsection
