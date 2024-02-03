@extends("shared.admin.dashboardLayout")

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
</style>
@endsection

@section("content")

    <div class="d-flex" style="height: 100vh;">
        <div class="col-1 sidebar">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        会员管理
                    </button>
                  </h2>
                  <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <ul>
                        <li>
                            <a href="{{ route("memberList") }}">会员列表</a>
                    
                        </li>
                        <li>
                            <a href="#">会员等级</a>
                    
                        </li>
                    </ul>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                      客服管理
                    </button>
                  </h2>
                  <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <ul>
                        <li>
                            <a href="{{ route("customerService") }}">客服管理</a>
                        </li>
                    </ul>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-11">
            <div class="container">
                <p class="alert alert-success d-none successAlert"></p>
                <p class="alert alert-danger d-none dangerAlert"></p>
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
                            <th>ID</th>
                            <th>名称</th>
                            <th>图标</th>
                            <th>会员价格</th>
                            <th>佣金比例</th>
                            <th>最小余额</th>
                            <th>接单次数</th>
                            <th>提现次数</th>
                            <th>提现最小金额</th>
                            <th>提现最大金额</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($vip) > 0)
                            @foreach($vip as $v)
                                <tr>
                                    <td>{{ $v["id"] }}</td>
                                    <td>{{ $v["name"] }}</td>
                                    <td><img src="{{$v["img_path"]}}" alt=""></td>
                                    <td>{{ number_format($v["price"], 2, '.', ',') }}</td>
                                    <td>{{ $v["commission"] }}</td>
                                    <td>{{ number_format($v["min_balance"], 2, '.', ',') }}</td>
                                    <td>{{ $v["task_number"] }}</td>
                                    <td>{{ $v["withdraw_number"] }}</td>
                                    <td>{{ number_format($v["min_withdraw"], 2, '.', ',') }}</td>
                                    <td>{{ number_format($v["max_withdraw"], 2, '.', ',') }}</td>
                                    <td>
                                        <button class="btn btn-success editBtn" value="{{ $v["id"] }}">编辑</button>
                                        <a href="/admin/deleteVip/{{$v["id"]}}" class="btn btn-danger">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">等级编辑</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text text-danger idErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">名称</span>
                    <input type="text" class="form-control name" placeholder="请输入升级价格" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger nameErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">升级价格</span>
                    <input type="number" class="form-control price" placeholder="请输入升级价格" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger priceErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">接单限制</span>
                    <input type="number" class="form-control task_number" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger taskNumberErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">佣金比例</span>
                    <input type="number" class="form-control commission" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger commissionErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">提现次数</span>
                    <input type="number" class="form-control withdraw_number" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger withdrawNumberErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">提现最小限制</span>
                    <input type="number" class="form-control min_withdraw" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger minWithdrawErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">提现最大限制</span>
                    <input type="number" class="form-control max_withdraw" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger maxWithdrawErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">用户余额限制</span>
                    <input type="number" class="form-control min_balance" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger minBalanceErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">提现至少完成订单/天</span>
                    <input type="number" class="form-control min_task_number" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger minTaskNumberErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">提现手续费</span>
                    <input type="number" class="form-control withdraw_fee" placeholder="请输入接单限制" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger withdrawFeeErr"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary editSubmit">提交</button>
                <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
      </div>
@endsection

@section("script")
<script>
    $(function(){

        const displayErr = (errMsg, errEle) => {
            if(errMsg !== null && errMsg !== undefined)
                $(`#${errEle}Err`).html(errMsg[0]);
        }

        $(".editBtn").on("click", function(){
            let id = $(this).val();
            $(".editSubmit").val(id);

            $.ajax({
                "method" : "GET",
                "url" : `/admin/getVipInfo/${id}`,
                "dataType" : "json",
                "success" : function(res){
                    if(Object.keys(res).length <= 0){
                        window.location.realod();
                    }else{
                        let {
                            name,
                            price, 
                            commission, 
                            min_balance, 
                            task_number,
                            min_withdraw,
                            max_withdraw,
                            withdraw_fee,
                            min_task_number,
                            withdraw_number 
                        } = res;
                        $(".name").val(name);
                        $(".price").val(price);
                        $(".commission").val(commission);
                        $(".min_balance").val(min_balance);
                        $(".task_number").val(task_number);
                        $(".min_withdraw").val(min_withdraw);
                        $(".max_withdraw").val(max_withdraw);
                        $(".withdraw_fee").val(withdraw_fee);
                        $(".min_task_number").val(min_task_number);
                        $(".withdraw_number").val(withdraw_number);
                    }
                }
            })

            $(".modal").addClass("active");
        })

        $(".closeModal").on("click", function(){
            $(".modal").removeClass("active");
        })

        $(".editSubmit").on("click", function(){
            let name = $(".name").val(),
            price = $(".price").val(), 
            commission = $(".commission").val(),
            min_balance = $(".min_balance").val(), 
            task_number = $(".task_number").val(),
            min_withdraw = $(".min_withdraw").val(),
            max_withdraw = $(".max_withdraw").val(),
            withdraw_fee = $(".withdraw_fee").val(),
            min_task_number = $(".min_task_number").val(),
            withdraw_number = $(".withdraw_number").val();

            $.ajax({
                "method" : "POST", 
                "url" : "{{ route('editVip') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : {
                    "id" : $(this).val(),
                    "name" : name,
                    "price" : price,
                    "commission" : commission,
                    "min_balance" : min_balance,
                    "task_number" : task_number,
                    "min_withdraw" : min_withdraw,
                    "max_withdraw" : max_withdraw,
                    "withdraw_fee" : withdraw_fee,
                    "min_task_number" : min_task_number,
                    "withdraw_number" : withdraw_number
                },
                "success" : function(res){
                    if(res !== "success"){

                        let {id, name, price, commission, min_balance, task_number, min_withdraw, max_withdraw, withdraw_fee, min_task_number, withdraw_number} = res["errors"];
                        displayErr(id, "id");
                        displayErr(name, "name");
                        displayErr(price, "price");
                        displayErr(commission, "commission");
                        displayErr(min_balance, "minBalance");
                        displayErr(task_number, "taskNumber");
                        displayErr(min_withdraw, "minWithdraw");
                        displayErr(max_withdraw, "maxWithdraw");
                        displayErr(withdraw_fee, "withdrawFee");
                        displayErr(min_task_number, "minTaskNumber");
                        displayErr(withdraw_number, "withdrawNumber");
                    }else{
                        window.location.reload();
                    }
                }
            })
        })
    })
</script>
@endsection