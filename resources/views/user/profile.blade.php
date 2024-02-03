@extends("shared.user.layout")

@section("title")
PROFILE
@endsection

@section("style")
<style>
    .userInfoContainer{
        background-color: whitesmoke;
        padding: 1rem;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .userInfoContainer .profileImg{
        max-width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover
    }

    .userInfoContainer .row{
        text-align: center;
    }

    .userInfoContainer .row .col-6.left{
        border-right: 1px solid black;
    }

    .modalActionContainer{
        background-color: whitesmoke;
        padding: 2rem;
        border-radius: 1rem;
    }

    .withdrawInfoBtn, .depositBtn{
        border-bottom: 1px solid black;
    }

    .actionTitle{
        font-weight: 700
    }

    a.logoutBtn{
        background-color: var(--primary-color);
        padding: .5rem 1.2rem;
        text-decoration: none;
        color: var(--tertiary-text-color);
        border-radius: 10px;
    }

    .accountType{
        display: none;
    }

    .accountType.active{
        display: block;
    }

    .modal.active{
        display: block;
    }

    .modal-dialog, .modal-content{
        height: 90%;
    }

    .modal-header .btn-close{
        margin: 0;
    }

    .modal-content{
        background-color: var(--background-color);
    }

    .modal-header{
        color: var(--primary-text-color);
    }

    .withdrawNowTotalBalance, .depositNowTotalBalance, .withdrawHistoryModal .withdrawHistory,
    .depositHistoryModal .depositHistory{
        background-color:whitesmoke;
        padding: 2rem;
        border-radius: 20px;
    }

    .withdrawSubmitBtn{
        background-color: var(--primary-color);
    }

    .withdrawHistory, .withdrawNow, .depositHistory, .depositNow{
        color: var(--primary-text-color)
    }

    .withdrawHistoryModal, .withdrawNowModal, .depositHistoryModal, .depositNowModal{
        display: none;
    }

    :is(.withdrawHistoryModal, .withdrawNowModal, .depositHistoryModal, .depositNowModal).active{
        display: block;
    }

    .withdrawHistoryModal .withdrawHistory, .depositHistoryModal .depositHistory{
        color:black;
    }

    a.csLink{
        padding: 0.5rem 1.2rem;
        background-color: var(--primary-color);
        text-decoration: none;
        color: black;
    }

    /* .btn-close{
        --bs-btn-close-color: var(--primary-text-color);
    } */

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
    <div class="userInfoContainer">
        <img src="/img/background.png" alt="" class="profileImg mb-3">
        <p class="text name">{{ $data->name }}</p>
        <p class="text referralCode">Referral Code: {{ $data->referral_code }}</p>

        <div class="row w-100">
            <div class="col-6 left">
                <p class="price">$ {{ number_format($data->total_commission, 2, '.', ',') }}</p>
                <p class="price">Total Profit</p>
            </div>
            <div class="col-6">
                <p class="price">$ {{ number_format($data->balance, 2, '.', ',') }}</p>
                <p class="price">Total Balance</p>
            </div>
        </div>
    </div>

    <div class="modalActionContainer my-3">
        <p class="actionTitle">Transaction</p>
        <div class="withdrawBtn row">
            <div class="col-10">
                Withdraw
            </div>
            <div class="col-2">
                >
            </div>
        </div>
        <div class="depositBtn row">
            <div class="col-10">Deposit</div>
            <div class="col-2"> > </div>
        </div>
        <p class="actionTitle mt-4">Profile</p>
        {{-- <div class="editProfileBtn row">
            <div class="col-10">
                Edit Profile
            </div>
            <div class="col-2">
                >
            </div>
        </div> --}}
        <div class="withdrawInfoBtn row">
            <div class="col-10">Withdrawal Info</div>
            <div class="col-2"> > </div>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-start">
        <a href="{{route("logout")}}" class="logoutBtn">Log out</a>
    </div>
</div>

<div class="modal withdrawalInfoModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-caret-left closeModal"></i>
          <h5 class="modal-title">Withdrawal Info</h5>
        </div>
        <div class="modal-body">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">Account Type</span>
                <select class="form-select" id="accountType" aria-label="Default select example">
                    <option selected value="0">REVOLUT</option>
                    <option value="1">BTC</option>
                    <option value="2">WISE</option>
                    <option value="3">ERC</option>
                    <option value="4">TRC</option>
                  </select>
            </div>
            <p class="text text-danger" id="accountTypeErr"></p>
            <div class="accountType active accountType1">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">First Name</span>
                    <input type="text" class="form-control" id="firstName" placeholder="Enter your first name" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="firstNameErr"></p>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Last Name</span>
                    <input type="text" class="form-control" id="lastName" placeholder="Enter your last name" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="lastNameErr"></p>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Card Number</span>
                    <input type="number" class="form-control" id="cardNum" placeholder="Enter your card number" aria-label="" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="cardNumErr"></p>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">City</span>
                    <input type="text" class="form-control" id="city" placeholder="Enter your city" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="cityErr"></p>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Country</span>
                    <input type="text" class="form-control" id="country" placeholder="Enter your country" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="countryErr"></p>
            </div>
            <div class="accountType accountType2">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Name</span>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="nameErr"></p>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Email</span>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="emailErr"></p>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Phone No</span>
                    <input type="text" class="form-control" id="contact" placeholder="Enter your name" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="contactErr"></p>
            </div>
            <div class="accountType accountType3">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">Address</span>
                    <input type="text" class="form-control" id="address" placeholder="Enter your name" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
                <p class="text text-danger" id="addressErr"></p>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary withdrawalInfoSubmit">Save changes</button>
        </div>
      </div>
    </div>
</div>

<div class="modal withdrawalModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-caret-left closeModal"></i>
          <h5 class="modal-title">Withdrawal</h5>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-between text-center">
                <div class="col-6 withdrawNow">Withdraw Now</div>
                <div class="col-6 withdrawHistory">Withdraw History</div>

            </div>
            <div class="withdrawNowModal active mt-3">
                <div class="withdrawNowTotalBalance">
                    <p class="title">TOTAL BALANCE</p>
                    <p class="text text-success">$ {{ number_format($data["balance"], 2, ".", ",") }}</p>
                    <p>*You will receive your withdrawal within an hour</p>
                </div>

                <div class="withdrawNowForm d-flex flex-column align-items-center mt-3">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">Withdraw Amount</span>
                        <input type="number" class="form-control" id="withdrawAmount" placeholder="Enter your withdraw amount" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    <p class="text text-danger" id="withdrawAmountErr"></p>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">Withdraw Password</span>
                        <input type="password" class="form-control" id="withdrawPassword" placeholder="Enter your withdraw password" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    <p class="text text-danger" id="withdrawPasswordErr"></p>
                    <button class="btn withdrawSubmitBtn">Submit</button>
                </div>
            </div>
            <div class="withdrawHistoryModal mt3">
                @foreach($withdraw_history as $wh)
                    <div class="withdrawHistory text-center">
                        <div class="row">
                            <div class="col-6">
                                {{$wh["created_at"]}}
                            </div>
                            <div class="col-6">
                                @if($wh["status"] === 0)
                                    REJECTED
                                @elseif($wh["status"] === 1)
                                    APPROVED
                                @else
                                    PENDING
                                @endif
                            </div>
                        </div>
                        <p>$ {{ number_format($wh["amount"], 2, ".", ",") }}</p>
                    </div>
                @endforeach
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal depositModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-caret-left closeModal"></i>
          <h5 class="modal-title">Deposit</h5>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-between text-center">
                <div class="col-6 depositNow">Deposit Now</div>
                <div class="col-6 depositHistory">Deposit History</div>
            </div>
            <div class="depositNowModal active mt-3">
                <div class="depositNowTotalBalance">
                    <p class="title">TOTAL BALANCE</p>
                    <p class="text text-success">$ {{ number_format($data["balance"], 2, ".", ",") }}</p>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="{{ route("customerService") }}" class="csLink mt-5">Contact Customer Service</a>
                </div>
            </div>
            <div class="depositHistoryModal mt-3">
                @foreach($deposit as $wh)
                    <div class="depositHistory text-center">
                        <div class="row">
                            <div class="col-6">
                                {{$wh["created_at"]}}
                            </div>
                            <div class="col-6">
                                DONE
                            </div>
                        </div>
                        <p>$ {{ number_format($wh["amount"], 2, ".", ",") }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
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
        $(".closeModal").on("click", function(){
            $(".modal").removeClass("active");
        })

        $(".withdrawInfoBtn").on("click", function(){
            $(".withdrawalInfoModal").addClass("active");   
        })
        $("#accountType").on("change", function(){
            $(".accountType").removeClass("active");

            let value = $(this).val();
            if(value === "0"){
                $(".accountType1").addClass("active");
            }else if(value === "2"){
                $(".accountType2").addClass("active");
            }else{
                $(".accountType3").addClass("active");
            }
        })

        $(".withdrawalInfoSubmit").on("click", function(){
            let firstName = $("#firstName").val(), lastName = $("#lastName").val(),
            cardNum = $("#cardNum").val(),  city = $("#city").val(),
            country = $("#country").val(), name = $("#name").val(), email = $("#email").val(),
            contact = $("#contact").val(), address= $("#address").val(), type = $("#accountType").val();

            $.ajax({
                "method" : "POST",
                "url" : "{{ route('submitWithdrawInfo') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : {
                    "type" : type,
                    "firstName" : firstName,
                    "lastName" : lastName,
                    "cardNum" : cardNum,
                    "city" : city,
                    "country" : country,
                    "name" : name,
                    "email" : email,
                    "contact" : contact,
                    "address" : address
                },
                "success" : function(res){
                    if(res === "success"){
                        window.location.reload();
                    }
                }
            })
        })

        $(".withdrawBtn").on("click", function(){
            $(".withdrawalModal").addClass("active");
        })

        $(".withdrawSubmitBtn").on("click", function(){
            let amount = $("#withdrawAmount").val(), password = $("#withdrawPassword").val();

            $.ajax({
                "method" : "POST",
                "url" : "{{ route('submitWithdraw') }}",
                "dataType" : "json",
                "headers" : {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                "data" : {
                    "amount" : amount,
                    "password" : password
                },
                "successs" : function(res){
                    if(Object.keys(res).length <= 0 || res === "success"){
                        window.location.reload();
                    }else if(res !== "success"){
                        let {amount, password} = res["errors"];
                        displayErr(amount, "withdrawAmount");
                        displayErr(password, "withdrawPasswordAmount");
                    }
                }
            })
        })

        $(".withdrawNow").on("click", function(){
            $(".withdrawNowModal").addClass("active");
            $(".withdrawHistoryModal").removeClass("active");
        })

        $(".withdrawHistory").on("click", function(){
            $(".withdrawHistoryModal").addClass("active");
            $(".withdrawNowModal").removeClass("active");
        })

        $(".depositBtn").on("click", function(){
            $(".depositModal").addClass("active");
        })

        $(".depositNow").on("click", function(){
            $(".depositNowModal").addClass("active");
            $(".depositHistoryModal").removeClass("active");
        })

        $(".depositHistory").on("click", function(){
            $(".depositHistoryModal").addClass("active");
            $(".depositNowModal").removeClass("active");
        })
    })
</script>
@endsection