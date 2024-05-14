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
                <h4>My Clients' Payments
                    {{-- <button type="button" class="btn-primary bg-primary rounded-circle" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        <i class="bi bi-plus"></i>
                    </button> --}}
                </h4>
                <div>
                    <input type="text" class="form-control" placeholder="Search ...">
                </div>
            </div>
            <div class="card-body">
                @include('payments_table')
                <div class="row">
                    <div class="col-12 text-center">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentDetails" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Payment Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p style="font-weight: bold;" class="text-bold">Payment Date: <span style="font-weight: normal;"
                                    id="paymentDate">....</span>
                            </p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <p style="font-weight: bold;" class="text-bold">Client: <span style="font-weight: normal;"
                                    id="clientName">....</span></p>
                            <p style="font-weight: bold;" class="text-bold">Reciept No: <span style="font-weight: normal;"
                                    id="receiptNo">....</span></p>
                            <p style="font-weight: bold;" class="text-bold">Reference: <span style="font-weight: normal;"
                                    id="referenceNo">....</span>
                            </p>

                        </div>
                        <div class="col-12 col-sm-6">
                            <p style="font-weight: bold;" class="text-bold">Mode: <span style="font-weight: normal;"
                                    id="paymentMode">....</span></p>
                            <p style="font-weight: bold;" class="text-bold">Amount: <span style="font-weight: normal;"
                                    id="paymentAmount">....</span></p>
                            <p style="font-weight: bold;" class="text-bold">status: <span class="badge"
                                    style="font-weight: normal;" id="paymentStatus">....</span></p>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 col-sm-6">
                                    <p style="font-weight: bold;" class="text-bold">Bank: <span style="font-weight: normal;"
                                            id="bankname">....</span></p>
                                </div>
                                <div class="col-6 col-sm-6">
                                    <p style="font-weight: bold;" class="text-bold">Account: <span
                                            style="font-weight: normal;" id="accountnumber">....</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        <input type="text" name="bank_name" class="form-control"
                                            id="clientbankinput">
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
                    <div id="validationErrors"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.view-payment-btn').click(function() {
                var paymentId = $(this).data('payment-id');
                $.ajax({
                    url: "{{ route('agent.showpayment', ['payment' => ':paymentId']) }}".replace(
                        ':paymentId', paymentId),
                    type: 'GET',
                    success: function(response) {
                        // Populate the modal with the fetched response
                        $('#clientName').text(response.client);
                        $('#receiptNo').text(response.sys_ref);
                        $('#referenceNo').text(response.reference);
                        $('#paymentMode').text(response.mode);
                        $('#paymentAmount').text(response.amount);
                        $('#paymentStatus').text(response.status);
                        $('#paymentDate').text(response.date);
                        $('#bankname').text(response.bank_name);
                        $('#accountnumber').text(response.account_no);
                        $('#paymentStatus').removeClass('bg-danger bg-warning bg-success');
                        if (response.status == 'Unallocated') {
                            $('#paymentStatus').addClass('bg-danger');
                        } else if (response.status == 'Partially Allocated') {
                            $('#paymentStatus').addClass('bg-warning');
                        } else if (response.status == 'Fully Allocated') {
                            $('#paymentStatus').addClass('bg-success');
                        }
                        // Open the modal after successful AJAX request
                        $('#paymentDetails').modal('show');
                    },
                    error: function() {
                        alert('Failed to fetch payment details');
                    }
                });
            });
            $('.edit-payment-btn').click(function() {
                var paymentID = $(this).data('editpayment-id');
                $.ajax({
                    url: "{{ route('agent.showpayment', ['payment' => ':paymentId']) }}".replace(
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
                console.log(formData);
                console.log(paymentID);

                $.ajax({
                    url: "{{ route('agent.updatepayment', ['payment' => ':paymentId']) }}".replace(
                        ':paymentId', paymentID),
                    type: 'POST',
                    data: formData, // Send the serialized form data
                    success: function(response) {
                        console.log(response);
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
