@extends('shared.admin.layout')

@section('content')
    
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route("addAdmin") }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="name" value="{{ old("name") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Password</label>
                <input type="text" class="form-control" id="" name="password" value="{{ old("password") }}">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form> 
    </div>
@endsection