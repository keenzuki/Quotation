@extends('layouts.app')
<style>
    table caption a {
        text-decoration: none;
        font-family: Georgia, 'Times New Roman', Times, serif;
        font-size: 18px;
        color: black;
        border-radius: 10%;
        cursor: pointer;
    }

    a.roles.active {
        text-decoration: none;
        padding: 3px;
        font-family: Georgia, 'Times New Roman', Times, serif;
        font-size: 18px;
        background-color: rgb(255, 132, 0);
        color: white;
        border-radius: 10%;
    }
</style>
@section('content')
    @include('components.success')
    <ol class="breadcrumb d-inline-flex bg-transparent mt-2">
        <li class="breadcrumb-item"><a class="dash-link" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="text-white">System Users</h5>
        </div>
        <div class="row d-flex flex-nowrap mt-3 px-4">
            <div class="col">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Add
                </button>
            </div>
            <div class="col-md-3 col-5">
                <input type="text" class="form-control" id="searchUser" placeholder="Search...">
            </div>
        </div>
        <div class="card-body table-responsive">
            <table id="users" class="table caption-top table-striped">
                <caption class="fw-bold">
                    <a class="roles badge text-dark fs-6 rounded-pill active" data-role-id="0">All</a> |
                    <a class="roles badge text-dark fs-6 rounded-pill" data-role-id="1">Admin</a> |
                    <a class="roles badge text-dark fs-6 rounded-pill" data-role-id="2">Busines</a> |
                    <a class="roles badge text-dark fs-6 rounded-pill" data-role-id="3">Rider</a> |
                    <a class="roles badge text-dark fs-6 rounded-pill" data-role-id="4">Customer</a>
                </caption>
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th id="rolefield" scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="row text-center">
            </div>
        </div>
    </div>
    <!-- Modal -->
    {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Register New Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.registeruser') }}" method="post">
                        @csrf
                        <div class="row mb-2 g-2">
                            <div class="col-12">
                                <label for="role">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role"
                                    name="role">
                                    <option selected disabled value="">Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="first_name">First Name:</label>
                                <input value="{{ old('first_name') }}" type="text"
                                    class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                                    name="first_name" placeholder="Eg. John">
                                @error('first_name')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name">Last Name:</label>
                                <input value="{{ old('last_name') }}" type="text"
                                    class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                                    name="last_name" placeholder="Eg. Doe">
                                @error('last_name')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email:</label>
                                <input value="{{ old('email') }}" type="text"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email">
                                @error('email')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email_confirmation">Confirm Email:</label>
                                <input value="{{ old('email_confirmation') }}" type="text"
                                    class="form-control @error('email_confirmation') is-invalid @enderror"
                                    id="email_confirmation" name="email_confirmation">
                                @error('email_confirmation')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone">Phone:</label>
                            <input value="{{ old('phone') }}" type="number"
                                class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone">
                            @error('phone')
                                <span class="text-center text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row d-none" id="locationField">
                            <div class="col-md-6">
                                <label for="location">Location:</label>
                                <select class="form-select @error('location') is-invalid @enderror" id="location"
                                    name="location">
                                    <option selected disabled value="">Location</option>
                                    @foreach ($locations as $location)
                                        <option {{ old('location') == $location->id ? 'selected' : '' }}
                                            value="{{ $location->id }}">{{ $location->location_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 d-none" id="locationPoint">
                                <label for="point">Location Point:</label>
                                <input value="{{ old('point') }}" type="text"
                                    class="form-control @error('point') is-invalid @enderror" id="point"
                                    name="location_point">
                                @error('location_point')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row d-none" id="accountDetails">
                            <div class="col-md-6">
                                <label for="paybill">Office Paybill:</label>
                                <input value="{{ old('paybill') }}" type="text"
                                    class="form-control @error('paybill') is-invalid @enderror" id="paybill"
                                    name="paybill">
                                @error('paybill')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="account_number">Office Account No:</label>
                                <input value="{{ old('account_number') }}" type="text"
                                    class="form-control @error('account_number') is-invalid @enderror" id="account_number"
                                    name="account_number">
                                @error('account_number')
                                    <span class="text-center text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-flex me-0 mt-3">Register</button>

                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <script>
        $(document).ready(function() {
            $('#role').on('change', function() {
                var selectedRole = $(this).val();
                if (selectedRole === '3' || selectedRole === '2' || selectedRole === '6') {
                    $('#locationField').removeClass('d-none');
                    if (selectedRole === '2' || selectedRole === '6') {
                        $('#locationPoint').removeClass('d-none');
                        if (selectedRole === '6') {
                            $('#accountDetails').removeClass('d-none');
                        } else {
                            $('#accountDetails').addClass('d-none');
                        }
                    } else {
                        $('#locationPoint').addClass('d-none');
                    }
                } else {
                    $('#locationField').addClass('d-none');
                }
            });

            $('.roles').on('click', function(e) {
                e.preventDefault();
                $('.roles').removeClass('active');
                $(this).addClass('active');
                var roleID = $(this).data('role-id');
                tableData(roleID);
            });

            function populateTable(response, role) {
                var users = response.users;
                console.log('ready to populate');
                $('#users tbody').empty();
                $('#rolefield').addClass('d-none')
                $.each(response.users, function(index, user) {
                    var row = '<tr>';
                    row += '<td>' + (index + 1) + '</td>';
                    row += '<td>' + user.fname + '</td>';
                    row += '<td>' + user.lname + '</td>';

                    // Display the role column only when retrieving all users
                    if (role == 0) {
                        row += '<td>' + user.role + '</td>';
                        $('#rolefield').removeClass('d-none');
                    }

                    // Condition for approve button
                    var approveBtnText = user.status ? 'Block' : 'Unblock';
                    var approveBtnClass = user.status ? 'btn bg-danger' :
                        'btn bg-success';
                    var approveBtnURL = '/admin/users/' + user.id +
                        '/update-status';

                    row += '<td><a href="' + approveBtnURL + '" class="' +
                        approveBtnClass + '">' + approveBtnText;

                    row += '</tr>';
                    $('#users tbody').append(row);
                });

            }

            function tableData(roleID) {
                $.ajax({
                    url: '/admin/users/filter/' + roleID,
                    method: 'GET',
                    success: function(response) {
                        if (response && response.users && response.users.length > 0) {
                            populateTable(response, roleID);
                        } else {
                            $('#users tbody').empty();
                            $('#users tbody').append(
                                '<tr><td colspan="5" class="text-primary text-center">No users found</td></tr>'
                            );
                        }
                    },
                    error: function(error) {
                        // You might want to handle this case by showing a message or clearing the table
                        $('#users tbody').empty();
                        $('#users tbody').append(
                            '<tr><td colspan="5" class="text-danger text-center">Error fetching users</td></tr>'
                        );
                    }
                });
            }

            $("#searchUser").keyup(function() {
                var input = $(this).val();
                $.ajax({
                    url: '/admin/users/search',
                    method: 'GET',
                    data: {
                        search: input
                    },
                    success: function(response) {
                        if (response && response.users && response.users.length > 0) {
                            var role = 0;
                            populateTable(response, role);
                        } else {
                            $('#users tbody').empty();
                            $('#users tbody').append(
                                '<tr><td colspan="5" class="text-primary text-center">No users found</td></tr>'
                            );
                        }
                    },
                    error: function(error) {
                        $('#users tbody').empty();
                        $('#users tbody').append(
                            '<tr><td colspan="5" class="text-danger text-center">Error fetching users</td></tr>'
                        );
                    }
                });
            });
            tableData(0);
        });
    </script>
@endsection
