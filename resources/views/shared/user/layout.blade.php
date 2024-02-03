<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @yield('link')
    <link rel="stylesheet" href="{{ asset("css/index.css") }}">
    @yield('style')
    <style>
        body{
            background: #262626;
        }
        
        header{
            text-align: center;
        }

        footer{
            background-color:whitesmoke;
        }

        footer .row{
            width: 50%;
            height: 50px
        }

        footer .startContainer{
            background-color: var(--primary-color);
            height: 70px;
            border-radius: 50%;
            margin-top:-25px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 1px 2px var(--background-color);
        }

        footer .row :is(.col-2, .col-3) a{
            color: var(--tertiary-text-color);
            text-decoration: none;
        }

        footer .row .col-3{
            font-size: 2rem;
            color: var(--primary-text-color);
        }

        footer p{
            margin-bottom: 0;
            font-size: .8rem
        }

        @media screen and (max-width: 425px){
            footer .row{
                width:100%;
            }
        }
    </style>
</head>
<body>
    @if(auth()->check())
    <header class="d-flex justify-content-between">
        <h1>TIDAL</h1>
        <h1>Credit: {{ Auth::user()->credit }}</h1>
    </header>
    @endif
    @yield('content')

    @if(auth()->check())
    <footer class="fixed-bottom d-flex justify-content-center align-items-center text-center">
        <div class="row justify-content-between">
            <div class="col-2">
                <a href="{{ route("index") }}">
                    <i class="bi bi-house"></i>
                    <p class="text">HOME</p>
                </a>
            </div>
            <div class="col-2">
                <a href="{{ route("record",3) }}">
                    <i class="bi bi-card-text"></i>
                    <p class="text">RECORD</p>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route("start") }}">
                    <div class="startContainer">
                        <i class="bi bi-rocket-takeoff"></i>
                    </div>
                </a>
            </div>
            <div class="col-2">
                <a href="{{ route("service") }}">
                    <i class="bi bi-chat-right-text"></i>
                    <p class="text">SERVICE</p>
                </a>
            </div>
            <div class="col-2">
                <a href="{{ route("profile") }}">
                    <i class="bi bi-person"></i>
                    <p class="text">PROFILE</p>
                </a>
            </div>
        </div>
    </footer>
    @endif
    @yield('script')

</body>
</html>