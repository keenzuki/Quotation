@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Invoices</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>My Clients Invoices
                    <button type="button" class="btn-primary bg-primary rounded-circle" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                        <i class="bi bi-plus"></i>
                    </button>
                </h4>
                <div>
                    <input type="text" class="form-control" placeholder="Search ...">
                </div>
            </div>
            <div class="card-body">
                @include('invoices_table')
                <div class="row">
                    <div class="col-12 text-center">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
            </div>
        </div>
    </div> --}}
@endsection
