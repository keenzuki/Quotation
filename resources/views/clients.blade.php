@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Clients</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Clients
                    @cannot('seeAdminCont', auth()->user())
                        <button type="button" class="btn-primary bg-primary rounded-circle" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">
                            <i class="bi bi-plus"></i>
                        </button>
                    @endcannot
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="clientsTable"
                        class="table table-striped table-hover table-borderless table-primary align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                @can('seeAdminCont', auth()->user())
                                    <th>Rep</th>
                                @endcan
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($clients as $key => $client)
                                <tr class="table-primary">
                                    <td scope="row">
                                        {{ $key + 1 }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>{{ $client->address }}</td>
                                    @can('seeAdminCont', auth()->user())
                                        <td>{{ $client->rep }}</td>
                                    @endcan
                                    <td>
                                        <a class="btn btn-primary"
                                            href="{{ route('clientprofile', ['client' => $client->id]) }}">profile</a>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="5" class="text-center text-info">No data available</td>
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
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Understood</button>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('#clientsTable').DataTable();
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
