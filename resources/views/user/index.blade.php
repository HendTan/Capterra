@extends('shared.user.layout')

@section('title')
    Home Page
@endsection

@section("style")
    <style>
        img{
            width:100%;
        }

        p{
            margin-bottom:0;
        }

        .links{
            text-align: center;
        }

        .links a{
            color: var(--primary-text-color);
            text-decoration: none;
        }

        .links i{
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        :is(.staffLevelContainer, .dailyCheckInContainer) h1{
            text-align: center;
        }

        .staffLevel{
            background-color:white;
            border-radius: 20px;
            padding: 1rem;
        }

        .staffLevel .row .col-3 img{
            width: 100%;
            height: 100%;;
        }

        .staffLevel .levelText{
            font-weight: 700;
        }

        .staffLevel a{
            color: var(--tertiary-text-color);
            text-decoration: none;
        }

        .staffLevel a i{
            color: var(--primary-color);
        }

        .progress, .progress-stacked{
            --bs-progress-bar-bg: var(--primary-color);
        }

        .dailyCheckIn img.vipImg{
            width: 30%;
        }

        .dailyCheckIn img.dailyCheckInImg{
            height: 100%;
        }

        .dailyCheckIn p{
            font-size: .8rem;
        }

        .dailyCheckIn p.small{
            font-size: .6rem;
        }
    </style>
@endsection

@section('content')
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


        <div class="container">
            
            <img src="{{ asset("/img/background.png") }}" alt="">
            <div class="links">
                <div class="row mt-5">
                    <div class="col-3"><a href="{{ route("start") }}">
                        <i class="bi bi-rocket-takeoff"></i>
                        STARTING
                    </a></div>
                    <div class="col-3"><a href="">
                        <i class="bi bi-patch-check"></i>
                        CERTIFICATE
                    </a></div>
                    <div class="col-3"><a href="">
                        <i class="bi bi-cash"></i>
                        WITHDRAW
                    </a></div>
                    <div class="col-3"><a href="">
                        <i class="bi bi-chat-right-text"></i>
                        CUSTOMER SERVICE
                    </a></div>
                </div>
                <div class="row mt-3">
                    <div class="col-3"><a href="">
                        <i class="bi bi-file-earmark-text"></i>
                        TERMS
                    </a></div>
                    <div class="col-3"><a href="">
                        <i class="bi bi-calendar-event"></i>
                        EVENTS
                    </a></div>
                    <div class="col-3"><a href="">
                        <i class="bi bi-question-circle"></i><br />
                        FAQ
                    </a></div>
                    <div class="col-3"><a href="">
                        <i class="bi bi-people"></i><br />
                        ABOUT US
                    </a></div>
                </div>
            </div>
            <div class="staffLevelContainer mt-5">
                <h1>STAFF LEVEL</h1>

                <div class="staffLevel">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ asset("/img/vip1.png") }}" alt="">
                        </div>
                        <div class="col-3">
                            <img src="{{ asset("/img/vip2.png") }}" alt="">
                        </div>
                        <div class="col-3">
                            <img src="{{ asset("/img/vip3.png") }}" alt="">
                        </div>
                        <div class="col-3">
                            <img src="{{ asset("/img/vip4.png") }}" alt="">
                        </div>
                    </div>
                    <p class="mt-3 levelText">Level 1</p>
                    <div class="row mt-3">
                        <div class="col-6">
                            <p class="small">Per Deal Profit 0.5%</p>
                            <p class="small">40 Tasks Completed/day</p>
                        </div>
                        <div class="col-6 d-flex align-items-end justify-content-end">
                            <a href="">
                                VIEW MORE
                                <i class="bi bi-caret-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dailyCheckInContainer mt-5">
                <h1>DAILY CHECK IN</h1>

                <div class="dailyCheckIn staffLevel">
                    <div class="row">
                        <div class="col-6">
                            <img class="dailyCheckInImg" src="{{ asset("/img/dailyCheckin.png") }}" alt="">
                        </div>
                        <div class="col-6">
                            <p>Progess(1/4)</p>
                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: 25%"></div>
                              </div>
                            <img class="vipImg mt-3" src="{{ asset("/img/vip1.png") }}" alt="">
                            <p class="">Per Deal Profit 0.5%</p>
                            <p>40 Tasks Completed/day</p>
                            <p class="mt-3 small">
                                Once you complete two sets of product data in a day, our customer service team
                                will register a working day for you.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="staffLevelContainer partnerContainer my-5">
                <h1>Partnership</h1>
                <div class="row">
                    <div class="col-3">
                        <img src="{{ asset("/img/vip1.png") }}" alt="">
                    </div>
                    <div class="col-3">
                        <img src="{{ asset("/img/vip1.png") }}" alt="">
                    </div>
                    <div class="col-3">
                        <img src="{{ asset("/img/vip1.png") }}" alt="">
                    </div>
                    <div class="col-3">
                        <img src="{{ asset("/img/vip1.png") }}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('logout') }}" class="btn btn-success">Logout</a>
    </div>
@endsection
