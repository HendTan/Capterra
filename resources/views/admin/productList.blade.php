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
                            <a href="{{ route("dealControl") }}">交易控制</a>
                        </li>
                        <li>
                            <a href="">提现类别</a>
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
                            <a href="#">商品列表</a>
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
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success addBtn">添加商品</button>
                </div>

                <table class="table table-striped-column align-middle mt-3">
                    <thead>
                        <tr>
                            <th>商品ID</th>
                            <th>商品名称</th>
                            <th>商品价格</th>
                            <th>添加时间</th>
                            <th>状态</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($products) > 0)
                            @foreach($products as $p)
                                <tr>
                                    <td>{{ $p["id"] }}</td>
                                    <td>{{ $p["name"] }}</td>
                                    <td>${{ number_format($p["price"], 2, '.', ',') }}</td>
                                    <td>{{ $p["created_at"] }}</td>
                                    <td>
                                        <button class="btn btn-success editProduct" value="{{$p["id"]}}">编辑</button>
                                        <a href="/admin/deleteProduct/{{$p["id"]}}" class="btn btn-danger">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{ $products->links() }}
            </div>
        </div>
    </div>
    <div class="modal createProductModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">添加商品</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">商品名称</span>
                    <input type="text" class="form-control" id="name" placeholder="请输入商品名称" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="nameErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">商品价格</span>
                    <input type="number" class="form-control" id="price" placeholder="请输入商品价格" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="priceErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">商品照片</span>
                    <input type="file" id="productImg" class="form-control" id="inputGroupFile01" accept="image/png image/jpg image/jpeg">
                </div>
                <img src="" alt="" class="productImgPreview">
                <p class="text text-danger" id="imageErr"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary productSubmit">Save changes</button>
                <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal editProductModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">编辑商品</h5>
              <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text text-danger" id="editIdErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">商品名称</span>
                    <input type="text" class="form-control" id="editName" placeholder="请输入商品名称" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="editNameErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">商品价格</span>
                    <input type="number" class="form-control" id="editPrice" placeholder="请输入商品价格" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <p class="text text-danger" id="editPriceErr"></p>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">商品照片</span>
                    <input type="file" id="editProductImg" class="form-control" id="inputGroupFile01" accept="image/png image/jpg image/jpeg">
                </div>
                <img src="" alt="" class="editProductImgPreview">
                <p class="text text-danger" id="editImageErr"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary editProductSubmit">提交</button>
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

        $(".addBtn").on("click", function(){
            $(".createProductModal").addClass("active");
        })

        $(".closeModal").on("click", function(){
            $(".modal").removeClass("active");
        })

        $("#productImg").on("change", function(e){
            let file = e.target.files[0];
            if(file){
                $(".productImgPreview").attr("src", URL.createObjectURL(file))
            }
        })

        $("#editProductImg").on("change", function(e){
            let file = e.target.files[0];
            if(file){
                $(".editProductImgPreview").attr("src", URL.createObjectURL(file))
            }
        })

        $(".productSubmit").on("click", function(){
            let name = $("#name").val(), price = $("#price").val(), image = document.getElementById("productImg").files[0];
            let newForm = new FormData();
            newForm.append("image", image);
            newForm.append("name", name);
            newForm.append("price", price);
            $.ajax({
                "method": "POST",
                "url" : "{{ route('addProduct') }}",
                "contentType" : false,
                "processData" : false,
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : newForm,
                "success" : function(res){
                    if(res !== "success"){
                        let {image, name, price} = res["errors"];

                        displayErr(image, "image");
                        displayErr(name, "name");
                        displayErr(price, "price");
                    }else{
                        window.location.reload();
                    }
                }
            })
        })

        $(".editProduct").on("click", function(){
            let product_id = $(this).val();
            $(".editProductModal").addClass("active");
            $(".editProductSubmit").val(product_id);

            $.ajax({
                "method" : "GET",
                "url" : `/admin/getProductInfo/${product_id}`,
                "dataType" : "json",
                "success" : function(res){
                    if(Object.keys(res).length < 1){
                        window.location.reload();
                    }else{
                        let {name, price, img_path} = res;
                        console.log()
                        $("#editName").val(name);
                        $("#editPrice").val(price);
                        $(".editProductImgPreview").attr("src", img_path);
                    }
                }
            })
        })

        $(".editProductSubmit").on("click", function(){
            let product_id = $(this).val(), name = $("#editName").val(), price = $("#editPrice").val(), image = document.getElementById("editProductImg").files[0];

            let newForm = new FormData();
            newForm.append("product_id", product_id)
            newForm.append("image", image);
            newForm.append("name", name);
            newForm.append("price", price);
            $.ajax({
                "method": "POST",
                "url" : "{{ route('editProduct') }}",
                "contentType" : false,
                "processData" : false,
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : newForm,
                "success" : function(res){
                    if(res !== "success"){
                        let {image, name, price, product_id} = res["errors"];

                        displayErr(image, "editImage");
                        displayErr(name, "editName");
                        displayErr(price, "editPrice");
                        displayErr(product_id, "editId");
                    }else{
                        window.location.reload();
                    }
                }
            })
        })
    })
</script>
@endsection