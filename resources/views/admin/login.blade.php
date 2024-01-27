@extends('shared.admin.layout')

@section("style")
    <style>
        .container{
            height: 100vh;
        }
        .container form{
            width: 100%;
            max-width: 300px;
        }
    </style>
@endsection

@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('failed'))
            <div class="alert alert-danger">
                {{ session('failed') }}
            </div>
        @endif
        <h1 class="mb-5">系统管理</h1>
        <form action="{{ route('adminLogin') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" placeholder="登入账号" aria-label="Username" aria-describedby="basic-addon1" name="name" value="{{ old("name")}}">
                @error('name')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" placeholder="登入密码" aria-label="Password" aria-describedby="basic-addon1" name="password" value="{{ old("password")}}">
                @error('password')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
@endsection
