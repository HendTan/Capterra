@extends('shared.user.layout')

@section('title')
    Home Page
@endsection

@section('content')
    Homepage
    <div class="container">
        @if (session('fail'))
            <div class="alert alert-danger" role="alert">
                {{ session('fail') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('logout') }}" class="btn btn-success">Logout</a>
    </div>
@endsection
