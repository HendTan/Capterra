@extends('shared.user.layout')

@section("title")
    Start
@endsection

@section("style")
<style>
    body{
        text-align: center
    }
    .bigIcon{
        font-size:12rem;
        color:var(--primary-text-color)
    }

    .btn{
        background-color: var(--primary-color);
        border-radius: 50px;
    }

    .btn:hover{
        background-color:transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
    }


    .info{
        background-color: whitesmoke;
        padding: 1rem;
        border-radius: 1rem;
    }

    .modal-dialog{
        width: 90%;
    }

    .modal-content{
        background-color:whitesmoke;
    }

    .modal.active{
        background: transparent;
        backdrop-filter: blur(2px);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .number{
        font-weight: 700;
        font-size: 1.2rem;
    }

    .songImg{
        max-width: 100px;
    }
</style>
@endsection

@section("content")
<div class="container">
    @if(session("fail"))
        <p class="alert alert-danger dangerAlert" 
        x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
            {{ session("fail") }}
        </p>
    @endif

    @if(session("success"))
        <p class="alert alert-success dangerAlert" 
        x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
            {{ session("success") }}
        </p>
    @endif
    <i class="bi bi-window bigIcon"></i> <br />

    <button class="btn startBtn">STARTING</button>

    <div class="infoContainer mt-5">
        <h1>YOUR PERFORMANCE</h1>
        <div class="info mt-3">
            <div class="row">
                <div class="col-6">
                    <p class="number">{{ $user["today_commission"] }}</p>
                    <p class="text">TODAY COMMISSION</p>
                </div>
                <div class="col-6">
                    <p class="number">{{ $user["task_number"] }}</p>
                    <p class="text">TODAY ORDER QUANTITY</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <p class="number">{{ $user["total_commission"] }}</p>
                    <p class="text">TOTAL COMMISSION</p>
                </div>
                <div class="col-6">
                    <p class="number">{{ $user["team_commission"] }}</p>
                    <p class="text">TEAM COMMISSION</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img src="" class="songImg" alt="">
          <p class="songName my-3">Test 123</p>
          <div class="row priceInfo">
            <div class="col-6 left">
                <p class="number price">$62.00</p>
                <p class="text">Total Amount</p>
            </div>
            <div class="col-6 right">
                <p class="number commission">$0.31</p>
                <p class="text">Profit</p>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn modalSubmit">Submit</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section("script")
<script>
    $(function(){
        $(".startBtn").on("click", function(){
            $(".modal").addClass("active");
            $.ajax({
                "method" : "GET",
                "url" : "{{ route('getTask') }}",
                "dataType" : "json",
                "success" : function(res){
                    console.log(res);
                    if(Object.keys(res).length > 0){
                        let {name, price, img_path} = res["product"];
                        $(".songName").html(name);
                        $(".price").html(`$ ${(Math.round(price * 100) / 100).toFixed(2)}`);
                        $(".songImg").attr("src", img_path);
                        $(".commission").html(`$ ${res["task"]["commission"]}`);
                        $(".modalSubmit").val(res["task"]["id"]);
                    }else{
                        window.location.reload();
                    }
                }
            })
        })

        $(".closeModal").on("click", function(){
            $(".modal").removeClass("active");
        })

        $(".modalSubmit").on("click", function(){
            $.ajax({
                "method" : "POST",
                "url" : `/submitTask/${$(this).val()}`,
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "success" : function(res){
            
                    window.location = res;
                }
            })
        })
    })
</script>
@endsection