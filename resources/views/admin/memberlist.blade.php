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

    .productIDSelect{
        border: 1px black solid;
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
                            <a href="#">会员列表</a>
                        </li>
                        <li>
                            <a href="{{ route("memberVip") }}">会员等级</a>
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
                <div class="d-flex justify-content-end align-items-end">
                    <button class="btn btn-success addMemberModalBtn">添加会员</button>
                </div>
                <table class="table table-striped-column align-middle">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>账号</td>
                            <td>账号余额</td>
                            <td>邀请码</td>
                            <td>今日订单数</td>
                            <td>注册IP</td>
                            <td>最后交易日期</td>
                            <td>操作</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($user) > 0)
                            @for($i = 0; $i < count($user); $i++)
                                <td>{{ $user[$i]["id"] }}</td>
                                <td>
                                    手机号： {{ $user[$i]["contact"] }} <br />
                                    用户名： {{ $user[$i]["name"] }} <br />
                                    上级用户： {{ $upperUsername[$i]}}
                                </td>
                                <td>
                                    余额：{{ $user[$i]["balance"] }} <br />
                                    冻结：{{ $user[$i]["freeze_amount"] }}
                                </td>
                                <td>
                                    <button class="btn btn-primary">{{ $user[$i]["referral_code"] }}</button> <br />
                                    直推人数: {{ $totalMembers[$i] }}
                                </td>
                                <td>{{ $user[$i]->task_number}} / {{ $user[$i]->vip->task_number}}</td>
                                <td>
                                    注册IP: {{ $user[$i]["register_ip"] }} <br />
                                    登入IP: {{ $user[$i]["login_ip"] }}
                                </td>
                                <td>
                                    注册时间： {{ $user[$i]["created_at"] }}
                                </td>
                                <td class="actionBtnContainer">
                                    <button value="{{ $user[$i]["id"] }}" class="btn btn-danger balanceModalBtn">加扣款</button>
                                    <button value="{{ $user[$i]["id"] }}" class="btn btn-success taskModalBtn">卡单设置</button>
                                    <button value="{{ $user[$i]["id"] }}" class="btn btn-success tasksModalBtn">连单设置</button>
                                    <button value="{{ $user[$i]["id"] }}" class="btn btn-success infoModalBtn">基础资料</button>
                                    {{-- <button value="{{ $user[$i]["id"] }}" class="btn btn-danger">查看团队</button> --}}
                                    <a href="/admin/renewTask/{{ $user[$i]["id"] }}" class="btn btn-danger">重置今天任务量</a>
                                    {{-- <button value="{{ $user[$i]["id"] }}" class="btn btn-primary">账变信息</button> --}}
                                    <button value="{{ $user[$i]["id"] }}" class="btn btn-danger loginModalBtn">修改登入密码</button>
                                    <button value="{{ $user[$i]["id"] }}" class="btn btn-danger withdrawModalBtn">修改支付密码</button>
                                    <button value="{{ $user[$i]["id"] }}" class="btn btn-danger creditModalBtn">信誉分调整</button>
                                    @if($user[$i]["is_banned"] === 0)
                                        <a href="/admin/ban/{{ $user[$i]["id"] }}" class="btn btn-danger banBtn">禁用</a>
                                    @else
                                    <a href="/admin/unban/{{ $user[$i]["id"] }}" class="btn btn-success">启用</a>
                                    @endif
                                </td>
                            @endfor
                        
                        @endif
                        
                    </tbody>
                </table>
                {{ $user->links() }}
            </div>
        </div>
    </div>
    <div class="modal balanceModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">加扣款</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text text-danger" id="balanceUserIdErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">变更类型</span>
                    <select class="form-select" aria-label="Default select example" id="balanceType">
                        <option value="0" selected>用户加款</option>
                        <option value="1">赠送余额</option>
                        <option value="2">扣除余额</option>
                    </select>
                </div>
                <p class="text text-danger" id="balanceType
                Err"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">变动金额</span>
                    <input type="number" id="balanceAmount" class="form-control" placeholder="请输入变动金额" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="balanceAmountErr"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary balanceSubmit">提交</button>
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal taskModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">卡单设置</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text text-danger" id="taskIdErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">卡单商品1</span>
                    <input type="number" value="0" id="productId1" class="form-control" placeholder="Product ID" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="productId1Err"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">今日第几单卡</span>
                    <input type="number" value="0" class="form-control productNum1" placeholder="请输入今日第几单卡" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
              <button type="button" class="btn btn-primary taskModalSubmit">提交</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal tasksModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">连单设置</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">今日第几单连单开始</span>
                        <input type="number" value="0" id="number" class="form-control" placeholder="Product ID" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <p class="text text-danger" id="numberErr"></p>
                    <span class="input-group-text" id="basic-addon1">卡单商品1</span>
                    <input type="number" value="0" id="taskProductId1" class="form-control" placeholder="Product ID" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="taksProductId1Err"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">卡单商品2</span>
                    <input type="number" value="0" id="taskProductId2" class="form-control" placeholder="Product ID" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="taksProductId2Err"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">卡单商品3</span>
                    <input type="number" value="0" id="taskProductId3" class="form-control" placeholder="Product ID" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="taksProductId3Err"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
              <button type="button" class="btn btn-primary tasksSubmit">提交</button>
            </div>
          </div>
        </div>
      </div>

    <div class="modal infoModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">基础资料</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text text-danger" id="infoUserIdErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">用户名称</span>
                    <input type="tel" id="infoName" class="form-control" placeholder="请输入用户名称" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="infoNameErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">手机号码</span>
                    <input type="tel" id="infoContact" class="form-control" placeholder="请输入电话号" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="infoContactErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">赠送充值百分比</span>
                    <input type="number" id="infoPercent" class="form-control" placeholder="请输入百分比" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="infoPercentErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">冻结金额</span>
                    <input type="number" id="infoFreeze" class="form-control" placeholder="请输入冻结金额" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="infoFreezeErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">交易状态</span>
                    <select class="form-select" aria-label="Default select example" id="infoStatus">
                        <option selected value="1">正常</option>
                        <option value="0">冻结</option>
                      </select>
                </div>
                <p class="text text-danger" id="infoStatusErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">上级ID</span>
                    <input type="number" id="infoReferral" class="form-control" placeholder="请输入上级ID" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="infoReferralErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">是否设置为代理</span>
                    <select class="form-select" aria-label="Default select example" id="infoAgent">
                        <option selected value="0">否</option>
                        <option value="1">是</option>
                      </select>
                </div>
                <p class="text text-danger" id="infoAgentErr"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary infoSubmit">提交</button>
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
    </div>
    
    <div class="modal loginModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <p class="text text-danger" id="loginUserIdErr"></p>
              <h5 class="modal-title">修改登入密码</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">密码</span>
                    <input type="password" id="loginPassword" class="form-control" placeholder="请输入密码" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>
            <p class="text text-danger" id="loginPasswordErr"></p>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary loginSubmit">提交</button>
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal withdrawModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">修改支付密码</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text text-danger" id="withdrawUserIdErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">密码</span>
                    <input type="password" id="withdrawPassword" class="form-control" placeholder="请输入密码" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="withdrawPasswordErr"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary withdrawSubmit">提交</button>
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal productModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
                <div class="input-group flex-nowrap">
                    <input type="number" class="form-control" id="productPrice" placeholder="请输入最低金额" aria-label="Username" aria-describedby="addon-wrapping">
                    <span class="input-group-text" id="productSearch"><i class="bi bi-search"></i></span>
                  </div>
                <table class="table table-striped-column align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>名字</th>
                            <th>价格</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @if(count($products) > 0)
                            @foreach($products as $p)
                                <tr>
                                    <td>
                                        <input class="form-check-input productIDSelect" value="{{ $p["id"]}}" type="radio" name="flexRadioDefault">
                                    </td>
                                    <td>
                                        {{ $p["id"] }}
                                    </td>
                                    <td>
                                        {{ $p["name"] }}
                                    </td>
                                    <td>
                                        {{ $p["price"] }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary productModalSubmit" value="">选择</button>
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal tasksProductModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
                <div class="input-group flex-nowrap">
                    <input type="number" class="form-control" id="productPrice" placeholder="请输入最低金额" aria-label="Username" aria-describedby="addon-wrapping">
                    <span class="input-group-text" id="productSearch"><i class="bi bi-search"></i></span>
                  </div>
                <table class="table table-striped-column align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>名字</th>
                            <th>价格</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @if(count($products) > 0)
                            @foreach($products as $p)
                                <tr>
                                    <td>
                                        <input class="form-check-input productIDSelect" value="{{ $p["id"]}}" type="radio" name="flexRadioDefault">
                                    </td>
                                    <td>
                                        {{ $p["id"] }}
                                    </td>
                                    <td>
                                        {{ $p["name"] }}
                                    </td>
                                    <td>
                                        {{ $p["price"] }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary tasksProductModalSubmit" value="">选择</button>
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal addMemberModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">添加会员</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">用户名称</span>
                    <input type="text" class="form-control addUsername" placeholder="请输入用户名称" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="addNameErr"></p>
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping">手机号码</span>
                    <input type="text" class="form-control addContact" placeholder="请输入手机号码" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id=" addContactErr"></p>
                <div class="input-group flex-nowrap mt-3">
                    <span class="input-group-text" id="addon-wrapping">登入密码</span>
                    <input type="password" class="form-control  addPassword" placeholder="请输入登入密码" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="addPasswordErr"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary addMemberSubmit">提交</button>
                <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal creditModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">信誉分调整</h5>
            </div>
            <div class="modal-body">
                <form action="{{ route("editCredit") }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="userId" class="form-control" placeholder="请输入信誉分" aria-label="Username" aria-describedby="addon-wrapping">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">信誉分</span>
                        <input type="number" name="credit" class="form-control" placeholder="请输入信誉分" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mt-3">提交</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
@endsection

@section("script")
<script>
    $(function(){
        const getCSRF = () => {
            return $("input[name='_token']").val();
        }

        const displayErr = (errMsg, errEle) => {
            if(errMsg !== null && errMsg !== undefined)
                $(`#${errEle}Err`).html(errMsg[0]);
        }

        $(".closeModal").on("click", function(){
            $(this).parent().parent().parent().parent().removeClass("active");
        })

        $(".balanceModalBtn").on("click", function(){
            $(".balanceModal").addClass("active");
            $(".balanceSubmit").val($(this).val());
        })

        $(".balanceSubmit").on("click", function(){
            console.log($(this).val());
            let type = $("#balanceType").val(), amount = $("#balanceAmount").val(), user_id = $(this).val();

            $.ajax({
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "method": "POST",
                "url" : "{{ route('editBalance') }}",
                "dataType" : "json",
                "data" : {
                    "user_id" : $(this).val(),
                    "type" : type,
                    "amount" : amount
                },
                "success" : function(res){
                    if(res !== "success"){
                        let { user_id, type, amount } = res["errors"];

                        displayErr(user_id, "balanceUserId");
                        displayErr(type, "balanceType");
                        displayErr(amount, "balanceAmount");
                    }else{
                        $(".balanceModal").removeClass("active");
                        $(".successAlert").html("已成功加扣款项。").removeClass("d-none");
                        setTimeout(() => {
                            $(".successAlert").addClass("d-none");
                        },3000);
                    }
                }
            })
        })

        $(".infoModalBtn").on("click", function(){
            let id = $(this).val();
            $.ajax({
                "type" : "GET",
                "url" : `/admin/getUserInfo/${id}`,
                "dataType" : "json",
                "success" : function(res){
                    if(res === "fail"){
                        $(".dangerAlert").html("用户ID错误。").removeClass("d-none");

                        setTimeout(() => {
                            $(".dangerAlert").addClass("d-none");
                        }, 3000);
                    }else{
                        let {name, contact, in_percentage, freeze_amount, deal_status, is_agent} = res;

                        $("#infoName").val(name);
                        $("#infoContact").val(contact);
                        $("#infoPercent").val(in_percentage);
                        $("#infoFreeze").val(freeze_amount);
                        $("#infoStatus").val(deal_status);
                        $("#infoAgent").val(is_agent);

                        $(".infoModal").addClass("active");
                        $(".infoSubmit").val(id);
                    }
                }

            })
        })

        $(".infoSubmit").on("click", function(){
            let contact = $("#infoContact").val(), in_percentage = $("#infoPercent").val(), freeze_amount = $("#infoFreeze").val(),
            deal_status = $("#infoStatus").val(), is_agent = $("#infoAgent").val(), name = $("#infoName").val(), user_id = $(this).val(), referral_id = $("#infoReferral").val();
            $.ajax({
                "method" : "POST",
                "url" : "{{ route('adminEditUser') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : {
                    "user_id" : user_id,
                    "name" : name,
                    "contact" : contact,
                    "in_percentage" : in_percentage,
                    "freeze_amount" : freeze_amount,
                    "deal_status" : deal_status,
                    "is_agent" : is_agent,
                    "referral_id" : referral_id
                },
                "success" : function(res){
                    if(res !== "success"){
                        let { user_id, name, contact, in_percentage, freeze_amount, deal_status,  is_agent, referral_id } = res["error"];

                        displayErr(user_id, "infoUserId");
                        displayErr(name, "infoName");
                        displayErr(in_percentage, "infoPercent");
                        displayErr(freeze_amount, "infoFreeze");
                        displayErr(deal_status, "infoStatus");
                        displayErr(is_agent, "infoAgent");
                        displayErr(contact, "infoContact");
                        displayErr(referral_id, "infoReferral");
                    }else{
                        $(".infoModal").removeClass("active");
                        $(".successAlert").html("已成功编辑用户基础资料。").removeClass("d-none");
                        setTimeout(() => {
                            $(".successAlert").addClass("d-none");
                        },3000);
                    }
                }
            })
        })

        $(".loginModalBtn").on("click", function(){
            $(".loginModal").addClass("active");
            $(".loginSubmit").val($(this).val());
        })

        $(".loginSubmit").on("click", function(){
            $.ajax({
                "method" : "POST",
                "url" : "{{ route('adminEditLogin') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data": {
                    "user_id" : $(this).val(),
                    "password" : $("#loginPassword").val(),
                },
                "success" : function(res){
                    $("#loginPassword").val("");
                    if(res !== "success"){
                        let {user_id, password} = res["error"];

                        displayErr(user_id, "loginUserId");
                        displayErr(password, "loginPassword");
                    }else{
                        $(".loginModal").removeClass("active");
                        $(".successAlert").html("已成功编辑用户登入密码。").removeClass("d-none");
                        setTimeout(() => {
                            $(".successAlert").addClass("d-none");
                        },3000);
                    }
                }
            })
        })

        $(".withdrawModalBtn").on("click", function(){
            $(".withdrawModal").addClass("active");
            $(".withdrawSubmit").val($(this).val());
        })

        $(".withdrawSubmit").on("click", function(){
            $.ajax({
                "method" : "POST",
                "url" : "{{ route('adminEditWithdraw') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data": {
                    "user_id" : $(this).val(),
                    "password" : $("#withdrawPassword").val(),
                },
                "success" : function(res){
                    $("#withdrawPassword").val()
                    if(res !== "success"){
                        let {user_id, password} = res["error"];

                        displayErr(user_id, "withdrawUserId");
                        displayErr(password, "withdrawPassword");
                    }else{
                        $(".withdrawModal").removeClass("active");
                        $(".successAlert").html("已成功编辑用户支付密码。").removeClass("d-none");
                        setTimeout(() => {
                            $(".successAlert").addClass("d-none");
                        },3000);
                    }
                }
            })
        })

        $(".taskModalBtn").on("click", function(){
            $(".taskModal").addClass("active");
            $(".taskModalSubmit").val($(this).val());
        })

        $("#productId1, #productId2, #productId3").on("click", function(){
            $(".productModal").addClass("active");
            switch($(this).attr("id")){
                case "productId1" : $(".productModal .productModalSubmit").val(0);break;
                case "productId2" : $(".productModal .productModalSubmit").val(1);break;
                case "productId3" : $(".productModal .productModalSubmit").val(2);break;
            }
        })

        $("#taskProductId1, #taskProductId2, #taskProductId3").on("click", function(){
            $(".tasksProductModal").addClass("active");
            switch($(this).attr("id")){
                case "taskProductId1" : $(".tasksProductModal .tasksProductModalSubmit").val(0);break;
                case "taskProductId2" : $(".tasksProductModal .tasksProductModalSubmit").val(1);break;
                case "taskProductId3" : $(".tasksProductModal .tasksProductModalSubmit").val(2);break;
            }
        })

        $(".tasksProductModalSubmit").on("click", function(){
            let value = parseInt($(this).val()), selectedId = $("input[type=radio]:checked").val();

            switch(value){
                case 0:$("#taskProductId1").val(selectedId);break;
                case 1:$("#taskProductId2").val(selectedId);break;
                case 2:$("#taskProductId3").val(selectedId);break;
            }

            $(".tasksProductModal").removeClass("active");
        })

        $(".productModalSubmit").on("click", function(){
            let value = parseInt($(this).val()), selectedId = $("input[type=radio]:checked").val();

            switch(value){
                case 0:$("#productId1").val(selectedId);break;
                case 1:$("#productId2").val(selectedId);break;
                case 2:$("#productId3").val(selectedId);break;
            }

            $(".productModal").removeClass("active");
        })

        $(".taskModalSubmit").on("click", function(){
            let user_id = $(this).val(), 
            productId1 = $("#productId1").val(),
            productNumber1 = $(".productNum1").val();

            $.ajax({
                "method" : "POST",
                "url" : "{{ route('addTask') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : {
                    "user_id" : user_id,
                    "productId1": productId1,
                    "productNum1" : productNumber1
                },
                "success" : function(res){
                    if(res !== "success"){
                        let {user_id, productId1, productNum1} = res["errors"];

                        displayErr(user_id, "taskID");
                        displayErr(productId1, "productId1");
                        displayErr(productNum1, "productNum1");
                    }else{
                        window.location.reload();
                    }
                }
            })
        })

        $(".addMemberModalBtn").on("click", function(){
            $(".addMemberModal").addClass("active");
        })

        $(".addMemberSubmit").on("click", function(){
            let name = $(".addUsername").val(), contact = $(".addContact").val(), password = $(".addPassword").val();
            
            $.ajax({
                "method" : "POST",
                "url" : "{{ route('addMember') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : {
                    "name" : name,
                    "contact" : contact,
                    "password" : password
                },
                "success" : function(res){

                    if(res !== "success"){
                        let {name, contact, password} = res["errors"];
                        displayErr(name, "editName");
                        displayErr(contact, "editContact");
                        displayErr(password, "editPassword");
                    }else{
                        window.location.reload();
                    }
                }
            })
        })

        $("#productSearch").on("click", function(){
            let value = $("#productPrice").val();

            $.ajax({
                "method" : "GET",
                "url" : "{{ route('productSearch') }}",
                "dataType" : "json",
                "data" : {
                    "price" : value
                },
                "success" : function(res){
                    console.log(res);
                    if(res.length > 0){
                        $("#productTableBody").empty();
                        for(let r of res){
                            let {id, name, price} = r;
                            
                            $("#productTableBody").append(`
                                    <tr>
                                        <td>
                                            <input class="form-check-input productIDSelect" value="${id}" type="radio" name="flexRadioDefault">
                                        </td>
                                        <td>
                                            ${id}
                                        </td>
                                        <td>
                                            ${name}
                                        </td>
                                        <td>
                                            ${price}
                                        </td>
                                    </tr>
                            `);
                        }
                    }
                }
            })
        })

        $(".tasksModalBtn").on("click", function(){
            $(".tasksModal").addClass("active");
        })

        $(".tasksProductModalSubmit").on("click", function(){
            let productId1 = $("#taskProductId1").val(), productId2 =  $("#taskProductId2").val(), 
            productId3 =  $("#taskProductId3").val(), number = $("#number").val();
            $.ajax({
                "method" : "POST",
                "url" : "{{ route('addTasks') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : {
                    "user_id" : user_id,
                    "productId1": productId1,
                    "productId2": productId2,
                    "productId3": productId3,
                    "number" : number
                },
                "success" : function(res){
                    if(res !== "success"){
                        let {user_id, productId1, productId2, productId3} = res["errors"];

                        displayErr(user_id, "taskID");
                        displayErr(productId1, "taksProductId1");
                        displayErr(productId2, "taksProductId2");
                        displayErr(productId3, "taksProductId3");
                    }else{
                        window.location.reload();
                    }
                }
            })
        })

        $(".creditModalBtn").on("click", function(){
            $(".creditModal").addClass("active");
            $("#userId").val($(this).val());
        })
    })
</script>
@endsection