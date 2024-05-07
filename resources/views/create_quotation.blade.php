@extends('layouts.app')
@section('content')
    <div class="col content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('agent.clients') }}">Clients</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quotation</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                <h4>{{ $client->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('agent.savequotation', ['client' => $client->id]) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for="title">Project Title</label>
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="cost">Price</label>
                            <input type="text" name="cost" id="cost" class="form-control">
                        </div>
                        <div class="col-12">
                            <label for="details">Details</label>
                            <textarea name="details" rows="5" class="form-control" id="details"></textarea>
                        </div>
                        <div class="col-12 text-center mt-2">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
