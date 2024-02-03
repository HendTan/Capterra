<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>系统管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @yield('link')
    @yield('style')
    <style>
        body{
            background: whitesmoke;
            margin: 0;
            padding: 0
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .sidebar .accordion, .col-11 .container{
            position:relative;
            top:10%;
        }
        .accordion-button,.accordion-item{
            background-color: transparent
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary position-fixed top-0 w-100">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Home</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              {{-- <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">后台首页</a>
              </li> --}}
              <li class="nav-item">
                <a class="nav-link" href="{{ route("memberList") }}">角色</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route("withdrawManage") }}">交易</a>
              </li>
              {{-- <li class="nav-item">
                <a class="nav-link" href="#">帮助中心</a>
              </li> --}}
            </ul>
            <div class="d-flex">
              <a href="{{ route("adminLogout") }}" class="btn btn-success">Logout</a>
            </div>
          </div>
        </div>
      </nav>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    @yield('script')
</body>
</html>