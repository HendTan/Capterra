@extends("shared.user.layout")

@section("title")
Record
@endsection

@section("style")
<style>
    .statusBtn{
        color: var(--primary-text-color);
        text-align: center;
    }
    
    .statusBtn.active{
        color: var(--primary-color);
    }

    .productContainer{
        background-color: whitesmoke;
        padding: 1rem;
        border-radius: 20px;
    }

    .spaceDiv{
        height:100px;
    }
</style>
@endsection

@section("content")
    <div class="container">
        <div class="row">
            <div class="col-3 statusBtn allBtn active">ALL</div>
            <div class="col-3 statusBtn pendingBtn">PENDING</div>
            <div class="col-3 statusBtn completeBtn">COMPLETED</div>
            <div class="col-3 statusBtn rejectBtn">REJECTED</div>
        </div>
        @foreach ($data as $d)
            @if($d["status"] === 0)
                <div class="recordContainer mt-5">
                    <div class="d-flex justify-content-between">
                        <p class="text text-danger">{{$d["created_at"]}}</p>
                        <button class="btn btn-outline-danger">REJECTED</button>
                    </div>

                    <div class="productContainer row mt-3 align-items-center">
                        <div class="col-4">
                            <img src="{{ $d->product->img_path}}" alt="">
                        </div>
                        <div class="col-8">
                            <p>{{ $d->product->name }}</p>
                            <div class="row">
                                <div class="col-6">
                                    <p>Order Total</p>
                                    <p>Order Profit</p>
                                </div>
                                <div class="col-6">
                                    
                                    <p>{{ number_format($d->product->price, 2, '.', ',')}}</p>
                                    <p class="text text-danger">${{ number_format($d->commission, 2, '.', ',') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($d["status"] === 1)
                <div class="recordContainer mt-5">
                    <div class="d-flex justify-content-between">
                        <p class="text text-success">{{$d["created_at"]}}</p>
                        <button class="btn btn-outline-success">COMPLETED</button>
                    </div>

                    <div class="productContainer row mt-3 align-items-center">
                        <div class="col-4">
                            <img src="{{ $d->product->img_path}}" alt="">
                        </div>
                        <div class="col-8">
                            <p>{{ $d->product->name }}</p>
                            <div class="row">
                                <div class="col-6">
                                    <p>Order Total</p>
                                    <p>Order Profit</p>
                                </div>
                                <div class="col-6">
                                    <p>$ {{ number_format($d->product->price, 2, '.', ',')}}</p>
                                    <p class="text text-success">${{ number_format($d->commission, 2, '.', ',') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="recordContainer mt-5">
                    <div class="d-flex justify-content-between">
                        <p class="text text-warning">{{$d["created_at"]}}</p>
                        <button class="btn btn-outline-warning">PENDINg</button>
                    </div>

                    <div class="productContainer row mt-3 align-items-center">
                        <div class="col-4">
                            <img src="{{ $d->product->img_path}}" alt="">
                        </div>
                        <div class="col-8">
                            <p>{{ $d->product->name }}</p>
                            <div class="row">
                                <div class="col-6">
                                    <p>Order Total</p>
                                    <p>Order Profit</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ number_format($d->product->price, 2, '.', ',')}}</p>
                                    <p class="text text-warning">${{ number_format($d->commission, 2, '.', ',') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        <div class="spaceDiv"></div>
    </div>
@endsection

@section("script")
<script>
    $(function(){
        $(".statusBtn").removeClass("active");
        if(window.location.pathname === "/record/3"){
            $(".allBtn").addClass("active");
        }else if(window.location.pathname === "/record/2"){
            $(".pendingBtn").addClass("active");
        }else if(window.location.pathname === "/record/1"){
            $(".completeBtn").addClass("active");
        }else if(window.location.pathname === "/record/0"){
            $(".rejectBtn").addClass("active");
        }
    })
</script>
@endsection