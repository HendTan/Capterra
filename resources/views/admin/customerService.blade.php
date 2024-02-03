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

    .radioDiv{
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
                            <a href="{{ route("memberList") }}">会员列表</a>
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
                            <a href="#">客服管理</a>
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

                <div class="d-flex justify-content-end align-items-center">
                    <button class="btn btn-success addCSBtn">添加客服</button>
                </div>

                <table class="table table-striped-column align-middle">
                  <thead>
                    <tr>
                      <th>链接</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  @if(count($customer_service) > 0)
                    @foreach($customer_service as $c)
                      <tr>
                        <th>{{ $c["link"] }}</th>
                        <th>
                          <button class="btn btn-success editCSBtn" value="{{ $c["id"] }}">编辑</button>
                          <a href="/admin/deleteCS/{{ $c["id"] }}" class="btn btn-danger">删除</a>
                        </th>
                      </tr>
                    @endforeach
                  @endif
                </table>
            </div>
        </div>
    </div>

    <div class="modal addCSModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">添加客服</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">名称</span>
                <input type="text" class="form-control name" placeholder="Username" aria-label="请输入名称" aria-describedby="addon-wrapping">
              </div>
              <p class="text text-danger" id="nameErr"></p>
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">手机号码</span>
                <input type="tel" class="form-control contact" placeholder="请输入手机号码" aria-label="Username" aria-describedby="addon-wrapping">
              </div>
              <p class="text text-danger" id="contactErr"></p>
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">类型</span>
                <div class="form-check form-check-inline">
                  <input class="form-check-input radioDiv" value="0" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                  <label class="form-check-label" for="flexRadioDefault1">
                    QQ
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input radioDiv" value="1" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                  <label class="form-check-label" for="flexRadioDefault2">
                    微信
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input radioDiv" value="2" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                  <label class="form-check-label" for="flexRadioDefault2">
                    链接
                  </label>
                </div>
              </div>
              <p class="text text-danger" id="typeErr"></p>
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">链接</span>
                <input type="tel" class="form-control link" placeholder="请输入链接" aria-label="Username" aria-describedby="addon-wrapping">
              </div>
              <p class="text text-danger" id="linkErr"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary addBtnSubmit">提交</button>
              <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">取消</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal editModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">编辑客服</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="text text-danger" id="editIdErr"></p>
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">名称</span>
                <input type="text" class="form-control editName" placeholder="Username" aria-label="请输入名称" aria-describedby="addon-wrapping">
              </div>
              <p class="text text-danger" id="editNameErr"></p>
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">手机号码</span>
                <input type="tel" class="form-control editContact" placeholder="请输入手机号码" aria-label="Username" aria-describedby="addon-wrapping">
              </div>
              <p class="text text-danger" id="editContactErr"></p>
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">类型</span>
                <div class="form-check form-check-inline">
                  <input class="form-check-input radioDiv" value="0" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                  <label class="form-check-label" for="flexRadioDefault1">
                    QQ
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input radioDiv" value="1" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                  <label class="form-check-label" for="flexRadioDefault2">
                    微信
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input radioDiv" value="2" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                  <label class="form-check-label" for="flexRadioDefault2">
                    链接
                  </label>
                </div>
              </div>
              <p class="text text-danger" id="editTypeErr"></p>
              <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">链接</span>
                <input type="tel" class="form-control editLink" placeholder="请输入链接" aria-label="Username" aria-describedby="addon-wrapping">
              </div>
              <p class="text text-danger" id="editLinkErr"></p>
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
        const getCSRF = () => {
            return $("input[name='_token']").val();
        }

        const displayErr = (errMsg, errEle) => {
            if(errMsg !== null && errMsg !== undefined)
                $(`#${errEle}Err`).html(errMsg[0]);
        }

        $(".addCSBtn").on("click", function(){
          $(".addCSModal").addClass("active");
        })

        $(".closeModal").on("click", function(){
          $(".modal").removeClass("active")
        })

        $(".addBtnSubmit").on("click", function(){
          let name = $(".name").val(), contact = $(".contact").val(), link = $(".link").val(), type = $(".addCSModal input.radioDiv[type=radio]:checked").val();

          $.ajax({
            "method" : "POST",
            "url" : "{{ route('addCS') }}",
            "dataType" : "json",
            "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "data" : {
              "name" : name,
              "contact" : contact,
              "link" : link,
              "type" : type
            },
            "success" : function(res){
              if(res !== "success"){
                let { name, contact, link, type} = res["errors"];
                displayErr(name, "name");
                displayErr(contact, "contact");
                displayErr(link, "link");
                
                displayErr(type, "type");
              }else{
                window.location.reload();
              }
            }
          })
        })

        $(".editCSBtn").on("click", function(){
          let id = $(this).val();

          $.ajax({
            "method" : "GET",
            "url" : `/admin/getCSInfo/${id}`,
            "dataType" : "json",
            "success": function(res){
              if(Object.keys(res).length <= 0){
                window.location.reload();
              }else{
                let {name, contact, link, type} = res;

                $(".editName").val(name);
                $(".editContact").val(contact);
                $(".editLink").val(link);
                $(`.editModal input.radioDiv[value=${type}]`).prop("checked", true);
              }
            }
          })

          $(".editModal").addClass('active');
          $(".editSubmit").val(id);
        })

        $(".editSubmit").on("click", function(){
          let id = $(this).val(),name = $(".editName").val(), contact = $(".editContact").val(), link = $(".editLink").val(), type = $(".editModal input.radioDiv[type=radio]:checked").val();

          $.ajax({
            "method" : "POST",
            "url" : "/admin/editCS",
            "dataType" : "json",
            "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "data" : {
              "id" : id,
              "name" : name,
              "contact" : contact,
              "link" : link,
              "type" : type
            },
            "success" : function(res){
              if(res !== "success"){
                let {id,name,contact,link,type} = res["errors"];

                displayErr(id, "editId");
                displayErr(name, "editName");
                displayErr(contact, "editContact");
                displayErr(link, "editLink");
                displayErr(type, "editType");
              }else{
                window.location.reload();
              }
            }
          })
        })
    })
</script>
@endsection