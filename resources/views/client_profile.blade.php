@extends('layouts.app')
<style>
    /* Hide the radio inputs */
    input[type="radio"] {
        display: none;
    }

    /* Style for selected label */
    input[type="radio"]:checked+label {
        background-color: #007bff;
        color: #fff;
    }
</style>
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('clients') }}">Clients</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Client Details</h4>
                <div>
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="triggerId"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Profile
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#profileUpdateModal">
                            <i class="bi bi-pencil-square text-primary"></i> Edit Profile
                        </button>
                        <a class="dropdown-item" href="{{ route('agent.addquotation', ['client' => $client->id]) }}"><i
                                class="bi bi-plus-circle text-success"></i> Add Quotation</a>
                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="bi bi-folder-plus text-primary"></i> Add Payment
                        </button>
                        <hr class="m-1">
                        <a class="dropdown-item" href="#"><i class="bi bi-receipt text-success"></i> Payment
                            Report</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="clientDetails"
                        class="table table-striped table-hover table-borderless table-primary align-middle">
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
                <h3>Projects & Activities</h3>
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table id="quotationsTable" class="table table-striped table-hover table-primary align-middle">
                        <thead class="table-light" style="position: sticky;">
                            <tr>
                                <th>#</th>
                                <th>Ref</th>
                                <th>Project/Activity</th>
                                <th>Price</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($quotations as $key => $quotation)
                                <tr class="table-primary">
                                    <td>{{ $key + 1 }}
                                    </td>
                                    <td><a class="text-decoration-none" href="">{{ $quotation->reference }}</a>
                                    </td>
                                    <td>{{ $quotation->title }}</td>
                                    <td>{{ $quotation->cost }}</td>
                                    <td>
                                        @if ($quotation->pay_status == 1)
                                            <span class="bg-danger badge">Unpaid</span>
                                        @elseif ($quotation->pay_status == 2)
                                            <span class="bg-warning badge">Partially Paid</span>
                                        @elseif ($quotation->pay_status == 3)
                                            <span class="bg-success badge">Fully Paid</span>
                                        @else
                                            <span class="bg-info badge">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($quotation->created_at)->format('D d M, Y') }}
                                    </td>
                                    <td>
                                        <div class="dropdown open">

                                            @if ($quotation->status == 1)
                                                <button class="btn btn-warning btn-sm dropdown-toggle" type="button"
                                                    id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Draft
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                                    <a class="dropdown-item text-primary"
                                                        href="{{ route('agent.editdraft', ['quotation' => $quotation->id]) }}"><i
                                                            class="bi bi-check-circle text-primary"></i>
                                                        Complete</a>
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
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                    id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Quotation
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                                    <a class="dropdown-item"
                                                        href="{{ route('quotationpdf', ['quotation' => $quotation->id]) }}"><i
                                                            class="fs-5 bi bi-eye text-success"></i> View
                                                        Quotation</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('agent.updateQ2I', ['quotation' => $quotation->id]) }}"
                                                        onclick="return confirm('Do you really want to convert this Quotation to Invoice direct?')"><i
                                                            class="fs-5 bi bi-cart-check text-success"></i> Make
                                                        Invoice</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('agent.editquotation', ['quotation' => $quotation->id]) }}"><i
                                                            class="fs-5 bi bi-pencil-square text-info"></i> Edit
                                                        Quotation</a>
                                                    <hr>
                                                    <form class="dropdown-item"
                                                        action="{{ route('agent.deletequotation', ['quotation' => $quotation->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="text-danger" type="submit"
                                                            onclick="return confirm('Please Confirm you want to delete the draft')"><i
                                                                class="fs-5 bi bi-trash text-danger"></i>
                                                            Delete</button>
                                                    </form>
                                                </div>
                                            @elseif ($quotation->status == 3)
                                                <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                                    id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Invoice
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                                    <a class="dropdown-item text-success"
                                                        href="{{ route('invoicepdf', ['invoice' => $quotation->id]) }}"><i
                                                            class="bi bi-pen text-success"></i> View
                                                        Invoice</a>
                                                    <a class="dropdown-item text-info"
                                                        href="{{ route('editinvoice', ['invoice' => $quotation->id]) }}"><i
                                                            class="bi bi-pencil-square text-info"></i> Edit
                                                        Invoice</a>
                                                    {{-- <a class="dropdown-item"
                                                        href="{{ route('agent.processinvoice', ['invoice' => $quotation->id]) }}"><i
                                                            class="fs-5 bi bi-check-circle text-info"></i>
                                                        Process Invoice</a> --}}
                                                </div>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="7" class="text-info text-center">No data available</td>
                            @endforelse
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
                <h2>Payments Section</h2>
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table id="paymentsTable" class="table table-striped table-hover table-primary align-middle">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <th>Ref</th>
                                <th>Mode</th>
                                <th>Bank</th>
                                <th>Account</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider overflow-auto" style="width: 50vh;">
                            @forelse ($payments as $key => $payment)
                                <tr class="table-primary">
                                    <td>{{ $key + 1 }}
                                    </td>
                                    <td><a class="text-decoration-none" href="">{{ $payment->reference }}</a>
                                    </td>
                                    <td>{{ $payment->mode }}</td>
                                    <td>{{ $payment->bank }}</td>
                                    <td>{{ $payment->account }}</td>
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
                                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('D d M, Y') }}</td>

                                    <td>
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                            <a href="{{ route('showpayment', ['payment' => $payment->id]) }}"
                                                class="dropdown-item view-payment-btn">
                                                <i class="fs-5 bi bi-eye text-success"></i> View Payment
                                            </a>
                                            <button type="button" class="dropdown-item edit-payment-btn"
                                                data-editpayment-id="{{ $payment->id }}">
                                                <i class="fs-5 bi bi-pencil-square text-success"></i> Edit Payment
                                            </button>
                                            @if ($payment->status == 1)
                                                <a class="dropdown-item"
                                                    href="{{ route('agent.processpayment', ['payment' => $payment->id]) }}"><i
                                                        class="fs-5 bi bi-check-circle text-info"></i>
                                                    Process Payment</a>
                                            @endif
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
                                <td colspan="9" class="text-center text-info">No data available</td>
                            @endforelse
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="profileUpdateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Client Profile</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('agent.updateclient', ['client' => $client->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-6">
                                <label for="name">Full name</label>
                                <input value="{{ $client->name }}" type="text" name="name" class="form-control"
                                    id="name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="phone">Phone</label>
                                <input value="{{ $client->phone }}" type="text" name="phone" class="form-control"
                                    id="phone">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="email">Email</label>
                                <input value="{{ $client->email }}" type="text" name="email" class="form-control"
                                    id="email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="address">Address</label>
                                <input value="{{ $client->address }}" type="text" name="address"
                                    class="form-control" id="address">
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Payment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('agent.addpayment', ['client' => $client->id]) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <input value="cash" type="radio" name="mode" id="cash" checked>
                                <label class="btn btn-outline-secondary" for="cash">Cash</label>

                                <input value="mpesa" type="radio" name="mode" id="mpesa">
                                <label class="btn btn-outline-secondary" for="mpesa">Mpesa</label>

                                <input value="cheque" type="radio" name="mode" id="cheque">
                                <label class="btn btn-outline-secondary" for="cheque">Cheque</label>

                                <input value="bank_deposit" type="radio" name="mode" id="bank_deposit">
                                <label class="btn btn-outline-secondary" for="bank_deposit">Bank Deposit</label>
                            </div>
                            <div id="reference_fields" class="col-12" style="display: none;">
                                <label for="reference">Reference</label>
                                <input type="text" name="reference" class="form-control" id="reference">
                            </div>
                            <div class="col-6">
                                <label for="amount">Amount</label>
                                <input type="number" min="1" name="amount" class="form-control"
                                    id="amount">
                            </div>
                            <div class="col-6">
                                <label for="date">Payment Date and Time</label>
                                <input type="datetime-local" name="date" class="form-control" id="date"
                                    value="{{ date('Y-m-d H:i:s') }}">
                            </div>
                            {{-- <div id="bank_fields" class="col-12" style="display: none;">
                                <div class="row"> --}}
                            <div class="col-6" id="bank_name" style="display: none;">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" id="bank">
                            </div>
                            <div class="col-6" id="account_no" style="display: none;">
                                <label for="account">Account Number</label>
                                <input type="text" name="account" class="form-control" id="account">
                            </div>
                            {{-- </div>
                            </div> --}}

                            <div class="col-12 text-center mt-2">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
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
                    <div id="validationErrors" class="text-danger"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#quotationsTable').DataTable();
        });
        $(document).ready(function() {
            $('#paymentsTable').DataTable();
        });
        $(document).ready(function() {
            $('input[name="mode"]').change(function() {
                var mode = $(this).val();
                $('#bank_name').hide();
                $('#account_no').show();
                $('#' + mode + '_fields').show();
                if (mode !== 'cash') {
                    $('#reference_fields').show();
                    if (mode == 'bank_deposit' || mode == 'cheque') {
                        $('#bank_name').show();
                        $('#account_no').show();
                    }
                } else {
                    $('#reference_fields').hide();
                }
            });
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
        });
    </script>
@endsection
