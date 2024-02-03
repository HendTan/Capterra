@extends("shared.admin.layout")

@section("content")
<div class="container">
    <form action="{{route("addDealControl")}}" method="POST">
        @csrf
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">最低信誉</span>
            <input type="text" class="form-control" name="min_cedit" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">交易所需余额</span>
            <input type="text" class="form-control" name="min_balance" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">卡单交易佣金倍数</span>
            <input type="text" class="form-control" name="commission_multiply" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">匹配最低范围</span>
            <input type="text" class="form-control" name="min_task_price" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">匹配最高范围</span>
            <input type="text" class="form-control" name="max_task_price" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">注册赠送</span>
            <input type="text" class="form-control" name="register_free" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">会员佣金</span>
            <input type="text" class="form-control" name="member_commission" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">商城状态</span>
            <input type="text" class="form-control" name="shop_status" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <button type="submit" class="btn btn-success">Submite</button>
    </form>
</div>
@endsection