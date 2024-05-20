@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payments</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Clients' Payments
                    {{-- <button type="button" class="btn-primary bg-primary rounded-circle" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        <i class="bi bi-plus"></i>
                    </button> --}}
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="paymentsTable"
                        class="table table-striped table-hover table-borderless table-primary align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Sys Ref</th>
                                <th>Ref</th>
                                <th>Mode</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Status</th>
                                @can('seeAdminCont', auth()->user())
                                    <th>Rep</th>
                                @endcan
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($payments as $payment)
                                <tr class="table-primary">
                                    <td scope="row">
                                        {{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $payment->sys_ref }}</td>
                                    <td>{{ $payment->reference }}</td>
                                    <td>{{ $payment->mode }}</td>
                                    <td>{{ $payment->client }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>
                                        @if ($payment->status == 1)
                                            <span class="bg-danger badge">Unallocated</span>
                                        @elseif ($payment->status == 2)
                                            <span class="bg-warning badge">Partially Allocated</span>
                                        @elseif ($payment->status == 3)
                                            <span class="bg-success badge">Fully Allocated</span>
                                        @else
                                            <span class="bg-info badge">N/A</span>
                                        @endif
                                    </td>
                                    @can('seeAdminCont', auth()->user())
                                        <td>{{ $payment->rep }}</td>
                                    @endcan
                                    <td>
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="triggerId"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                            <a href="{{ route('showpayment', ['payment' => $payment->id]) }}"
                                                class="dropdown-item view-payment-btn">
                                                <i class="fs-5 bi bi-eye text-success"></i> View Payment
                                            </a>

                                            @cannot('seeAdminCont', auth()->user())
                                                <button type="button" class="dropdown-item edit-payment-btn"
                                                    data-editpayment-id="{{ $payment->id }}">
                                                    <i class="fs-5 bi bi-pencil-square text-success"></i> Edit Payment
                                                </button>
                                            @endcannot
                                            <a class="dropdown-item"
                                                href="{{ route('agent.processpayment', ['payment' => $payment->id]) }}"><i
                                                    class="fs-5 bi bi-check-circle text-info"></i>
                                                Process Payment</a>

                                            @if ($payment->status == 1)
                                                <hr>
                                                <form class="dropdown-item" action="" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-danger" type="submit"
                                                        onclick="return confirm('Please Confirm you want to delete the draft')"><i
                                                            class="fs-5 bi bi-trash text-danger"></i>
                                                        Delete Payment</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="8" class="text-center text-info">No data available</td>
                            @endforelse
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editPaymentDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="clientName" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="clientNameEdit">....</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editpaymentdetailsform">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label for="phone">Receipt Number</label>
                                <input readonly type="text" name="phone" class="form-control"
                                    id="clientreceiptnoinput">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="clientmodeinput">Mode</label>
                                <input readonly type="text" name="mode" class="form-control" id="clientmodeinput">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="clientreferenceinput">Reference</label>
                                <input type="text" name="reference" class="form-control" id="clientreferenceinput">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="clientamountinput">Amount</label>
                                <input type="text" name="amount" class="form-control" id="clientamountinput">
                            </div>
                            <div class="col-12" id="bankinputfields">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <label for="clientbankinput">Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" id="clientbankinput">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="clientaccountinput">Account</label>
                                        <input type="text" name="account_no" class="form-control"
                                            id="clientaccountinput">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center mt-2">
                                <button type="submit" id="submitbtn" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                    <div id="validationErrors" class="text-danger"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#paymentsTable').DataTable();
            $('.edit-payment-btn').click(function() {
                var paymentID = $(this).data('editpayment-id');
                $.ajax({
                    url: "{{ route('editpayment', ['payment' => ':paymentId']) }}".replace(
                        ':paymentId', paymentID),
                    type: 'GET',
                    success: function(response) {
                        $('#clientNameEdit').text(response.client);
                        $('#clientreceiptnoinput').val(response.sys_ref);
                        $('#clientmodeinput').val(response.mode);
                        $('#clientreferenceinput').val(response.reference);
                        $('#clientamountinput').val(response.amount);
                        // Assuming response contains bank name and account number
                        $('#clientbankinput').val(response.bank_name);
                        $('#clientaccountinput').val(response.account_no);
                        $('#bankinputfields').hide();
                        if (response.mode != 'cash') {
                            $('#bankinputfields').show();
                        }
                        $('#editpaymentdetailsform').data('payment-id', paymentID);
                        // Open the modal after successful AJAX request
                        $('#editPaymentDetails').modal('show');
                    },
                    error: function() {
                        alert('Failed to fetch payment details');
                    }
                });
            });

            $('#submitbtn').click(function(event) {
                event.preventDefault(); // Prevent the default form submission
                var formData = $('#editpaymentdetailsform').serialize();
                var paymentID = $('#editpaymentdetailsform').data('payment-id');

                $.ajax({
                    url: "{{ route('agent.updatepayment', ['payment' => ':paymentId']) }}".replace(
                        ':paymentId', paymentID),
                    type: 'POST',
                    data: formData, // Send the serialized form data
                    success: function(response) {
                        // Check if the update was successful
                        if (response.success) {
                            // If successful, close the modal
                            $('#editPaymentDetails').modal('hide');
                            // Optionally, you can reload the page or update the UI
                            location.reload(); // Reload the page
                        } else {
                            // If there are validation errors, display them in the modal
                            // Assuming you have an element with ID 'validationErrors' in your modal
                            $('#validationErrors').html(response.errors);
                        }
                    },
                    error: function(error) {
                        // alert('Failed to update payment');
                        console.log(error);
                    }
                });
            });


            $('form').submit(function(event) {
                // Prevent the form from submitting
                event.preventDefault();

                // Remove any existing error messages
                $('.error-message').remove();

                // Validation
                var name = $('#name').val().trim();
                var phone = $('#phone').val().trim();
                var email = $('#email').val().trim();
                var address = $('#address').val().trim();

                var nameRegex = /^[a-zA-Z\s]+$/;
                var phoneRegex = /^0\d{9}$/;
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                var addressRegex = /^[a-zA-Z0-9\s]+$/;

                var errors = [];

                if (!name.match(nameRegex)) {
                    errors.push('Please enter a valid name.');
                    $('#name').after('<div class="error-message text-danger">' + errors[errors.length - 1] +
                        '</div>');
                }

                if (!phone.match(phoneRegex)) {
                    errors.push(
                        'Please enter a valid phone number starting with 0 and containing 10 digits.');
                    $('#phone').after('<div class="error-message text-danger">' + errors[errors.length -
                        1] + '</div>');
                }

                if (!email.match(emailRegex)) {
                    errors.push('Please enter a valid email address.');
                    $('#email').after('<div class="error-message text-danger">' + errors[errors.length -
                        1] + '</div>');
                }

                if (!address.match(addressRegex)) {
                    errors.push('Please enter a valid address.');
                    $('#address').after('<div class="error-message text-danger">' + errors[errors.length -
                            1] +
                        '</div>');
                }

                if (errors.length === 0) {
                    // If no errors, submit the form
                    this.submit();
                }
            });
        });
    </script>
@endsection
