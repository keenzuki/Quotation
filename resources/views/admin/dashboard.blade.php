@extends('layouts.app')
<style>
    .content {
        font-family: 'Times New Roman', Times, serif;
        color: black;
    }
</style>
@section('content')
    <div class="col cont">
        @include('components.success')
        <div class="mt-3 ms-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <small>{{ __('Welcome Back ' . Auth::user()->role->name) }} &#x2764;</small>
                {{-- @if (session()->has('admin'))
                    <a href="{{ route('adminprofile') }}" class="btn btn-primary">Go back</a>
                @endif --}}
            </div>
        </div>
    </div>
@endsection
