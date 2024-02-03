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
                            <a href="#">提现管理</a>
                        </li>
                        <li>
                            <a href="{{ route("dealControl") }}">交易控制</a>
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
                <table class="table table-striped-column align-middle">
                    <thead>
                        <tr>
                            <th>提现用户</th>
                            <th>提现信息</th>
                            <th>订单状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdraw_history as $wh)
                        <tr>
                            <th>
                                提现等级： {{$wh->user->vip->id}} <br>
                                提现用户： {{$wh->user->name}} <br>
                                提现手机： {{$wh->user->contact}} <br>
                                提现上级： @if($wh->referral !== null) {{$wh->user->referral->name}} @endif
                            </th>
                            <th>
                                结算类型:  @if($wh->withdrawal->type === 0) REVOLUT
                                            @elseif($wh->withdrawal->type === 1) BTC
                                            @elseif($wh->withdrawal->type === 2) WISE
                                            @elseif($wh->withdrawal->type === 3) ERC
                                            @else TRC @endif
                                            <br>
                                @if($wh->withdrawal->type === 0) 
                                姓名： {{$wh->withdrawal->firstName}} {{$wh->withdrawal->lastName}} <br>
                                卡号: {{$wh->withdrawal->card_num}} <br>
                                @elseif($wh->withdrawal->type === 2) 
                                名称： {{$wh->withdrawal->name}} <br>
                                电子邮件：{{$wh->withdrawal->email}} <br>
                                手机号：{{$wh->withdrawal->contact}} <br>
                                @else 
                                地址： {{$wh->withdrawal->address}} <br>
                                @endif
                                提现金额: {{$wh->amount}}
                            </th>
                            <th>
                                @if($wh->status === 0)
                                    审核驳回
                                @elseif($wh->status === 1)
                                    提款成功
                                @else
                                    等待审核
                                @endif
                            </th>
                            <th>
                                @if($wh->status === 2)
                                <a href="/admin/approve/{{$wh->id}}" class="btn btn-success">通过</a>
                                <a href="/admin/reject/{{$wh->id}}" class="btn btn-warning">驳回</a>
                                @endif
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("script")
<script>
</script>
@endsection