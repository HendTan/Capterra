@extends("shared.user.layout")

@section("title")
    {{ $title }}
@endsection

@section("link")
<link rel="stylesheet" href="{{ asset("css/login.css") }}">
@endsection

@section('content')
    <div class="container">
        @if(session("fail"))
            <div class="alert alert-danger" role="alert">
                {{ session("fail")}}
            </div>
        @endif
        @if(session("success"))
            <div class="alert alert-success" role="alert">
                {{ session("success")}}
            </div>
        @endif
        <h1>TIDAL</h1>
        <div class="d-flex mb-3">
            <button class="actionBtn loginAction">LOGIN</button>
            <button class="actionBtn signupAction">REGISTER</button>
        </div>
        <div class="login">
            <form action="{{ route("login") }}" method="post">
                @csrf
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping" name="lusername" value="{{ old("lusername") }}">
                </div>
                @error('lusername')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="addon-wrapping" name="lpassword" value="{{ old("lpassword") }}">
                </div>
                @if(session("lpassword"))
                    <p class="text-danger mt-1">{{ session("lpassword") }}</p>
                @endif
                <button type="submit" class="loginBtn mt-3">Login</button>
            </form>
        </div>
        <div class="register">
            <form action="{{ route("signup") }}" method="post">
                @csrf
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping" name="username" value="{{ old("username") }}">
                </div>
                @error('username')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-phone"></i></span>
                    <input type="tel" class="form-control" placeholder="Phone No." aria-label="Username" aria-describedby="addon-wrapping" name="phone" value="{{ old("phone") }}">
                </div>
                @error('phone')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <div class="input-group flex-nowrap mt-3 radioDiv">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-gender-ambiguous"></i></span>
                    <div class="form-check ms-3">
                        <input class="form-check-input active" type="radio" id="maleValue" checked name="maleRadio">
                        <label class="form-check-label active" for="flexRadioDefault1">
                          Male
                        </label>
                      </div>
                      <div class="form-check ms-3">
                        <input class="form-check-input" type="radio" id="femaleValue" name="femaleRadio" >
                        <label class="form-check-label" for="flexRadioDefault1">
                          Female
                        </label>
                      </div>
                    </div>
                    @error('maleRadio')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Login Password" aria-label="Username" aria-describedby="addon-wrapping" name="password"  value="{{ old("password") }}">
                </div>
                @error('password')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Confirm Login Password" aria-label="Username" aria-describedby="addon-wrapping" name="confirmPassword"  value="{{ old("confirmPassword") }}">
                </div>
                @error('confirmPassword')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key"></i></span>
                    <input type="password" class="form-control" placeholder="Withdrawal Password" aria-label="Username" aria-describedby="addon-wrapping" name="withdraw" value="{{ old("withdraw") }}">
                </div>
                @error('withdraw')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key"></i></span>
                    <input type="password" class="form-control" placeholder="Confirm Withdrawal Password" aria-label="Username" aria-describedby="addon-wrapping"  name="confirmWithdraw" value="{{ old("confirmWithdraw") }}">
                </div>
                @error('confirmWithdraw')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-link-45deg"></i></span>
                    <input type="text" class="form-control" placeholder="Referral code" aria-label="Referral code" aria-describedby="addon-wrapping"  name="referral"  value="{{ old("referral") }}">
                </div>
                @error('referral')
                    <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
                <button type="submit" class="loginBtn mt-3">Sign up</button>
            </form>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(function(){
            $(".loginBtn").on("click", function(e){
                e.preventDefault();
                if($(this).html() === "Login"){
                    $(".login form").submit();
                }else if($(this).html() === "Sign up"){
                    $(".register form").submit();
                }
            })

            if(window.location.pathname === "/login"){
                $(".login, .loginAction").addClass("active");
            }else{
                $(".register, .signupAction").addClass("active");
            }
            $(".form-check-input").on("click", function(){
                let id = ($(this).attr("id") === "femaleValue") ? "maleValue" : "femaleValue";

                $(this).addClass("active");
                $(this).siblings(".form-check-label").addClass("active");

                $(`#${id}`).removeClass("active");
                $(`#${id}`).siblings(".form-check-label").removeClass("active");
            })

            $(".actionBtn").on("click", function(){
                for(let btn of $(".actionBtn")){
                    $(btn).removeClass("active");
                }
                $(this).addClass("active");

                if($(this).html() === "LOGIN"){
                    $(".login").addClass("active");
                    $(".register").removeClass("active");
                    window.location.pathname = "login";
                }else if($(this).html() === "REGISTER"){
                    $(".register").addClass("active");
                    $(".login").removeClass("active");
                    window.location.pathname = "signup";
                }
            })
        })
    </script>
@endsection