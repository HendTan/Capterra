@extends('shared.admin.dashboardLayout')

@section("style")
<style>
    .col-11 .container{
        max-width: 2000px;
    }
    .actionBtnContainer{
        max-width: 260px;
    }

    .actionBtnContainer .btn{
        --bs-btn-padding-x:0.5rem;
        margin: .5rem 0;
    }

    .modal.active{
        display: block;
        background: transparent;
        backdrop-filter: blur(2px);
        display: flex;
        justify-content: center;
        align-items:center;
    }

    .productImgPreview, .editProductImgPreview{
        max-width: 100px;
    }
</style>
@endsection

@section("content")

    <div class="d-flex" style="height: 100vh;">
        <div class="col-1 sidebar">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        交易管理
                    </button>
                  </h2>
                  <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <ul>
                        <li>
                            <a href="">提现管理</a>
                        </li>
                        <li>
                            <a href="#">交易控制</a>
                        </li>
                    </ul>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                      商品管理
                    </button>
                  </h2>
                  <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <ul>
                        <li>
                            <a href="{{ route("productList") }}">商品列表</a>
                        </li>
                    </ul>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-11">
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
                <form action="{{ route("editDealControl") }}" method="POST">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">最低信誉</span>
                        <input type="text" name="min_cedit" class="form-control" value="{{ $data["min_cedit"] }}" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('name')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">交易所需余额</span>
                        <input type="number" class="form-control" name="min_balance" value="{{ $data["min_balance"] }}" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('min_balance')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">卡单交易佣金倍数</span>
                        <input type="number" class="form-control" name="commission" value="{{ $data["commission_multiply"] }}" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('commission')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">匹配最低范围</span>
                        <input type="number" class="form-control"  name="min_task_price" value="{{ $data["min_task_price"] }}" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('min_task_price')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">匹配最高范围</span>
                        <input type="number" class="form-control" name="max_task_price" value="{{ $data["max_task_price"] }}" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('max_task_price')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">注册赠送</span>
                        <input type="number" class="form-control" name="register_free" value="{{ $data["register_free"] }}" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('register_free')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">会员佣金</span>
                        <input type="number" class="form-control" name="member_commission" value="{{ $data["member_commission"] }}" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('member_commission')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">商城状态</span>
                        <input type="number" name="shop_status"  value="{{ $data["shop_status"] }}" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    @error('shop_status')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="btn btn-success mt-5">提交</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section("script")
<script>
</script>
@endsection