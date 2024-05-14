@extends('layouts.app')
@section('content')
    <div class="col content">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
        <div class="card shadow">
            <div class="card-header">
                <h5>Profile Details</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ route('profile.update') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <label for="imageInput" style="cursor: pointer">
                                <img id="profilePhoto" src="{{ asset('images/profiles/' . $user->photo) }}" alt="no image"
                                    style="width: 140px; height: 140px; border-radius: 100%; object-fit:cover; overflow:hidden;">
                            </label>
                            <span class="fw-bold fs-5">{{ $user->role->name }}</span>
                        </div>
                        <input type="file" class="form-control " id="imageInput" name="image" style="display:none">
                    </div>
                    <div class="row mt-3 p-2">
                        <div class="d-flex flex-column text-center">
                            <span class="text-dark fs-4">Profile Information</span>
                        </div>
                        <div class="row mb-4 mt-4">
                            <div class="col-sm-6 mb-2">
                                <label for="plate">First Name:</label>
                                <input value="{{ old('first_name', $user->fname) }}" type="text" class="form-control"
                                    id="firstname" name="first_name">
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6 mb-2">
                                <label for="price">Last Name:</label>
                                <input value="{{ old('last_name', $user->lname) }}" type="text" class="form-control"
                                    id="lastname" name="last_name">
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-6 mb-2">
                                <label for="email">Email:</label>
                                <input value="{{ old('email', $user->email) }}" type="email" class="form-control"
                                    id="email" disabled>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6 mb-2">
                                <label for="plate">Phone:</label>
                                <input value="{{ old('phone', $user->phone) }}" type="text" class="form-control"
                                    id="phone" name="phone">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary mb-4 ms-2">Save</button>

                </form>

                <hr class="mx-5 px-2">

                <div class="row mt-3 p-2">
                    <div class="d-flex flex-column text-center">
                        <span class="text-dark fs-4">Update Password</span>
                        <small>Ensure your account is using a long, random password to stay safe.</small>
                    </div>

                    <form action="{{ route('password.update') }}" method="post">
                        @csrf
                        @method('put')
                        <div class="row mb-4 mt-4">
                            <div class="col-md-4 mb-2">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" id="current_password" class="form-control" name="current_password">
                                @error('current_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="inputNewPassword" class="form-label">New Password</label>
                                <input type="password" id="inputNewPassword" class="form-control" name="password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="inputConPassword" class="form-label">Confirm Password</label>
                                <input type="password" id="inputConPassword" class="form-control"
                                    name="password_confirmation">
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mb-4 ms-2"> Save</button>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.getElementById("profilePhoto");
                imgElement.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
