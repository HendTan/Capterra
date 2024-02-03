@extends('shared.admin.layout')

@section('content')
    
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route("addVip") }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="name" value="{{ old("name") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Price</label>
                <input type="text" class="form-control" id="" name="price" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Commission</label>
                <input type="text" class="form-control" id="" name="commission" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Min balance</label>
                <input type="text" class="form-control" id="" name="min_balance" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">task_number</label>
                <input type="text" class="form-control" id="" name="task_number" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">min_withdraw</label>
                <input type="text" class="form-control" id="" name="min_withdraw" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">max_withdraw</label>
                <input type="text" class="form-control" id="" name="max_withdraw" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">withdraw_fee</label>
                <input type="text" class="form-control" id="" name="withdraw_fee" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">min_task_number</label>
                <input type="text" class="form-control" id="" name="min_task_number" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">withdraw_number</label>
                <input type="text" class="form-control" id="" name="withdraw_number" value="{{ old("password") }}">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">img_path</label>
                <input type="text" class="form-control" id="" name="img_path" value="{{ old("password") }}">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form> 
    </div>
@endsection