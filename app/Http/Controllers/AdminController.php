<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\product;
use App\Models\vip;
use App\Models\customer_service as cs;
use App\Models\transaction_history as th;
use App\Models\task_queue as tq;
use App\Models\deal_control as dc;
use App\Models\withdrawal;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function index(Request $request){
        return view("admin.index");
    }

    public function memberList(Request $request){
        $users = users::where(function($query){
            $query->where("role", "=", 1)->orWhere("role", "=", 2);
        })->paginate(1);

        $upperUsername = [];
        $totalMembers = [];
        $i = 0;

        foreach($users as $u){
            $user = users::where("id", $u->referral_id)->first();
            $upperUsername[$i] = ($user !== null) ? $user->name : "null";
            $totalMember = count(users::where("referral_id", $u->id)->get());
            $totalMembers[$i] = ($totalMember > 0) ? $totalMember : 0;
            $i++;
        }

        return view("admin.memberlist",[
            "user" => $users,
            "upperUsername" => $upperUsername,
            "totalMembers" => $totalMembers,
            "products" => product::get()
        ]);
    }

    public function editBalance(Request $request){
        $validateType = Validator::make($request->all(), [
            "user_id" => "required|numeric|exists:users,id",
            "type" => "required|numeric|min:0|max:2",
            "amount" => "required|numeric|min:0"
        ]);

        if($validateType->fails()){
            return response()->json(["errors" => $validateType->errors()], 200);
        }

        $user = users::where("id", $request["user_id"])->first();

        $newTh = th::create([
            "user_id" => $request["user_id"],
            "type" => $request["type"],
            "amount" => $request["amount"],
            "account" => $user["contact"],
            "th_number" => $user["id"]
        ])->id;

        th::where("id", $newTh)->update([
            "th_number" => "0"
        ]);

        if((int) $request["type"] === 0 || (int) $request["type"] === 1){
            
            if((int) $request["type"] === 1){
                $user->balance += $user["in_percentage"] * $request["amount"];
            }else{
                $user->balance += $request["amount"];
            }

        }else{
            $user->balance -= $request["amount"];
        }

        $user->save();

        return response()->json("success", 200);
    }

    public function getUserInfo($id){
        $user = users::select("contact", "name", "in_percentage", "freeze_amount", "deal_status", "is_agent")->where("id", $id)->first();

        if($user === null){
            return response()->json("fail", 200);
        }

        return response()->json($user, 200);
    }

    public function adminEditUser(Request $request){
        $validation = Validator::make($request->all(), [
            "user_id" => "required|numeric|exists:users,id",
            "name" => [
                "required", "min:6", "max:20",
                Rule::unique("users")->ignore(users::where("id", $request["user_id"])->first()->id)
            ],
            "contact" => "required|min:1|max:25",
            "in_percentage" => "required|numeric|min:0|max:100",
            "freeze_amount" => "required|numeric|min:0",
            "deal_status" => "required|numeric|min:0|max:1",
            "is_agent" => "required|numeric|min:0|max:1",
            "referral_id" => "nullable|numeric|exists:users,id"
        ]);


        if($validation->fails()){
            return response()->json(["error" => $validation->errors()], 200);
        }

        users::where("id", $request["user_id"])->update([
            "name" => $request["name"],
            "contact" => $request["contact"],
            "in_percentage" => $request["in_percentage"],
            "freeze_amount" => $request["freeze_amount"],
            "deal_status" => $request["deal_status"],
            "is_agent" => $request["is_agent"],
            "referral_id" => $request["referral_id"]
        ]);

        return response()->json("success", 200);
    }

    public function adminEditLogin(Request $request){
        $validation = Validator::make($request->all(), [
            "user_id" => "required|numeric|exists:users,id",
            "password" => "required|min:8|max:16"
        ]);

        if($validation->fails()){
            return response()->json(["error" => $validation->errors()], 200);
        }

        users::where("id", $request["user_id"])->update([
            "password" => Hash::make($request["password"])
        ]);

        return response()->json("success", 200);
    }

    public function adminEditWithdraw(Request $request){
        $validation = Validator::make($request->all(), [
            "user_id" => "required|numeric|exists:users,id",
            "password" => "required|min:8|max:16"
        ]);

        if($validation->fails()){
            return response()->json(["error" => $validation->errors()], 200);
        }

        users::where("id", $request["user_id"])->update([
            "password" => Hash::make($request["password"])
        ]);

        return response()->json("success", 200);
    }

    public function unbanUser($id){
        $user = users::where("id", $id)->first();

        if($user === null){
            return redirect('/admin/memberList')->with("fail", "用户ID错误。");
        }

        $user["is_banned"] = 0;
        $user->save();

        return redirect('/admin/memberList')->with("success", "已启用用户。");
    }

    public function banUser($id){
        $user = users::where("id", $id)->first();

        if($user === null){
            return redirect('/admin/memberList')->with("fail", "用户ID错误。");
        }

        $user["is_banned"] = 1;
        $user->save();

        return redirect('/admin/memberList')->with("success", "已禁用用户。");
    }

    public function productList(Request $request){
        return view('admin.productList', ["products" => product::paginate(1)]);
    }

    public function addProduct(Request $request){
        $validation = Validator::make($request->all(),[
            "name" => "required|min:5|max:100",
            "price" => "required|numeric|min:1",
            "image" => "required|max:10000|mimes:png,jpg,jpeg"
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }

        $newProductId = product::create([
            "name" => $request->name,
            "price" => $request->price,
            "img_path" => "1",
        ])->id;

        $fileName = $newProductId . ".png";

        $filePath = $request->file("image")->storeAs("public/img", $fileName);
        error_log($filePath);

        product::where("id", $newProductId)->update([
            "img_path" => "/storage/img/{$fileName}"
        ]);

        $request->session()->flash('success', "商品已成功创建。");

        return response()->json("success", 200);
    }

    public function getProductInfo(Request $request, $id){
        $product = product::select("name", "price", "img_path")->where("id", $id)->first();
        if($product === null){
            $request->session()->flash('fail', "不存在此商品");
        }
        return response()->json($product, 200);
    }

    public function addTask(Request $request){
        $validation = Validator::make($request->all(), [
            "user_id" => "required|numeric|exists:users,id",
            "productId1" => "required|numeric",
            "productNum1" => "required|numeric",
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }

        $user = users::where("id", $request["user_id"])->first();
        if($request["productId1"] > 0 && ($request["productNum1"] >= $user->task_number && $request["productNum1"] <= $user->vip->task_number)){
            $product = product::where("id", $request["productId1"])->first();
            $newTQ = tq::create([
                "product_id" => $request["productId1"],
                "user_id" => $request["user_id"],
                "number" => $request["productNum1"],
                "deduction" => 1,
                "status" => 2,
                "commission" => $user->vip->commission * $product->price * dc::first()->commission
            ]);
            error_log($newTQ->number);
            $request->session()->flash('success', "已成功添加卡单");
        }else{
            $request->session()->flash('fail', "并未成功添加卡单");
        }
        

        // if($request["productId2"] > 0 && ($request["productNum2"] > $user->task_number && $request["productNum2"] < $user->vip->task_number)){
        //     tq::create([
        //         "product_id" => $request["productId2"],
        //         "user_id" => $request["user_id"],
        //         "number" => $request["productNum2"],
        //         "deduction" => 1
        //     ]);
        // }

        // if($request["productId3"] > 0 && ($request["productNum3"] > $user->task_number && $request["productNum3"] < $user->vip->task_number)){
        //     tq::create([
        //         "product_id" => $request["productId3"],
        //         "user_id" => $request["user_id"],
        //         "number" => $request["productNum3"],
        //         "deduction" => 1
        //     ]);
        // }

        

        return response()->json("success", 200);
    }


    public function editProduct(Request $request){
        $validation = Validator::make($request->all(),[
            "product_id" => "required|numeric|exists:product,id",
            "name" => "required|min:5|max:100",
            "price" => "required|numeric|min:1"
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }

        if($request->hasFile("image")){
            $validation = Validator::make($request->all(), [
                "image" => "required|max:10000|mimes:png,jpg,jpeg"
            ]);

            if($validation->fails()){
                return response()->json(["errors" => $validation->errors()], 200);
            }

            $filePath = $request->file("image")->storeAs("public/img", "{$request['product_id']}.png"); 
        }

        product::where("id", $request["product_id"])->update([
            "name" => $request["name"],
            "price" => $request["price"]
        ]);

        $request->session()->flash('success', "商品已成功编辑。");

        return response()->json("success", 200);
    }

    public function deleteProduct($id){
        if(Auth::check()){
            $product = product::where("id", $id)->first();
            if($product === null){
                return redirect("/admin/productList")->with("fail", "不存在此商品");
            }

            product::destroy($id);
            return redirect("/admin/productList")->with("success", "已成功删除商品");
        }

        return redirect("/admin/login")->with("failed", "You have to login to access the data.");
    }

    public function productSearch(Request $request){
        $validation = Validator::make($request->all(), [
            "price" => "required|numeric|min:1"
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }

        return response()->json(product::where("price", ">=", $request["price"])->get(), 200);
    }

    public function memberVip(){
        return view("admin.memberVip",["vip" => vip::get()]);
    }

    public function addVipView(){
        return view("admin.addVip");
    }

    public function addVip(Request $request){
        vip::create([
            "name" => $request["name"],
            "price" => $request["price"],
            "commission" => $request["commission"],
            "min_balance" => $request["min_balance"],
            "task_number" => $request["task_number"],
            "min_withdraw" => $request["min_withdraw"],
            "max_withdraw" => $request["max_withdraw"],
            "withdraw_fee" => $request["withdraw_fee"],
            "min_task_number" => $request["min_task_number"],
            "withdraw_number" => $request["withdraw_number"],
            "img_path" => $request["img_path"]
        ]);

        return redirect("/admin/addVip")->with("success", "success");
    }

    public function getVipInfo($id){
        $vip = vip::where("id", $id)->first();
        if($vip === null){
            $request->session()->flash('fail', "不存在此等级");
        }
        return response()->json($vip, 200);
    }

    public function editVip(Request $request){
         $validation = Validator::make($request->all(), [
            "id" => "required|numeric|exists:vip,id",
            "name" => "required|min:1|max:50",
            "price" => "required|numeric|min:0.1", 
            "commission" => "required|numeric|min:0.00001|max:1", 
            "min_balance" => "required|numeric|min:0.00001", 
            "task_number" => "required|numeric|min:1", 
            "min_withdraw" => "required|numeric|min:0.00001", 
            "max_withdraw" => "required|numeric|min:0.00001", 
            "withdraw_fee" => "required|numeric|min:0", 
            "min_task_number" => "required|numeric|min:1", 
            "withdraw_number" => "required|numeric|min:1"
         ]);

         if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
         }

         vip::where("id", $request["id"])->update([
            "name" => $request["name"],
            "price" => $request["price"],
            "commission" => $request["commission"],
            "min_balance" => $request["min_balance"],
            "task_number" => $request["task_number"],
            "min_withdraw" => $request["min_withdraw"],
            "max_withdraw" => $request["max_withdraw"],
            "withdraw_fee" => $request["withdraw_fee"],
            "min_task_number" => $request["min_task_number"],
            "withdraw_number" => $request["withdraw_number"],
         ]);

         $request->session()->flash('success', "已成功编辑等级");

         return response()->json("success", 200);
    }

    public function deleteVip($id){
        $vip = vip::where("id", $id)->first();

        if($vip === null){
            return redirect("/admin/memberVip")->with("fail", "不存此粗等级");
        }
        vip::drestory($id);
        return redirect("/admin/memberVip")->with("success", "已删除等级");
    }

    public function customerService(){
        return view("admin.customerService", ["customer_service" => cs::get()]);
    }

    public function addCS(Request $request){
        $validation = Validator::make($request->all(), [
            "name" => "required|min:1|max:20",
            "contact" => "required|min:1|max:25",
            "link" => "required|min:1|max:100",
            "type" => "required|numeric|min:0|max:2"
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }


        $newCS = new cs;

        $newCS->name = $request["name"];
        $newCS->contact = $request["contact"];
        $newCS->type = $request["type"];
        switch((int)$request["type"]){
            case 0: $newCS->link = "tencent://message/?uin={$request['link']}";break;
            case 1: $newCS->link = "weixin://dl/chat?{{$request['link']}}";break;
            case 2: $newCS->link = $request["link"];break;
        }

        $newCS->save();

        $request->session()->flash('success', "已添加新客服");

        return response()->json("success", 200);
    }

    public function getCSInfo(Request $request, $id){
        $cs = cs::where("id", $id)->first();

        if($cs === null){
            $request->session()->flash('fail', "不存在此客服");
        }

        return response()->json($cs, 200);
    }

    public function editCS(Request $request){
        $validation = Validator::make($request->all(), [
            "id" => "required|numeric|exists:customer_service,id",
            "name" => "required|min:1|max:20",
            "contact" => "required|min:1|max:25",
            "link" => "required|min:1|max:100",
            "type" => "required|numeric|min:0|max:2"
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }

        cs::where("id", $request["id"])->update([
            "name" => $request["name"],
            "contact" => $request["contact"],
            "link" => $request["link"],
            "type" => $request["type"] 
        ]);

        $request->session()->flash('success', "已成功编辑客服");

        return response()->json("success", 200);
    }

    public function deleteCS(Request $request, $id){
        $cs = cs::where("id", $id)->first();

        if($cs === null){
            $request->session()->flash('fail', "此客服不存在");
        }else{
            cs::destroy($id);
            $request->session()->flash("success", "已成功删除客服");
        }

        return redirect('/admin/customerService');
    }

    private function generateReferralCode(){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $referralCode = '';

        for ($i = 0; $i < 6; $i++) {
            $referralCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $referralCode;
    }

    public function addMember(Request $request){
        $validation = Validator::make($request->all(), [
            "name" => "required|min:6|max:20|unique:users,name",
            "contact" => "required|min:1|max:25",
            "password" => "required|min:8|max:16",
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }

        while(true){
            $referralCode = $this->generateReferralCode();
            if(users::where("referral_code", $referralCode)->first() === null){
                break;
            }
        }

        users::create([
            "name" => $request["name"],
            "contact" => $request["contact"],
            "password" => Hash::make($request["password"]),
            "role" => 2,
            "contact" => $request->contact,
            "gender" => 0,
            "withdrawal_pass" => Hash::make($request->password),
            "referral_code" => $referralCode,
            "register_ip" => $request->ip(),
            "login_ip" => $request->ip(),
            "balance" => dc::first()->register_free,
            "commission" => 0,
            "freeze_amount" => 0,
            "in_percentage" => 0,
            "deal_status" => 1,
            "is_agent" => 0,
            "is_banned" => 0,
            "vip_id" => 1,
            "task_number" => 0,
            "today_commission" => 0,
            "total_commission" => 0,
            "team_commission" => 0,
            "credit" => 100,
            "withdrawal_number" => 0
        ]);

        $request->session()->flash('success', "已成功添加用户");

        return response()->json("success", 200);
    }

    public function addDealControlView(){
        return view("admin.addDealControlView");
    }

    public function addDealControl(Request $request){

        dc::create([
            "min_cedit" => $request["min_cedit"],
            "min_balance" => $request["min_balance"],
            "commission_multiply" => $request["commission_multiply"],
            "min_task_price" => $request["min_task_price"],
            "max_task_price" => $request["max_task_price"],
            "register_free" => $request["register_free"],
            "member_commission" => $request["member_commission"],
            "shop_status" => $request["shop_status"],
        ]);

        return redirect("/admin/addDealControl");
    }

    public function dealControl(){
        return view("admin.dealControl", ["data" => dc::first()]);
    }

    public function editDealControl(Request $request){
        $validation = $request->validate([
            "min_cedit" => "required|numeric|min:0",
            "min_balance" => "required|numeric|min:0",
            "commission_multiply" => "required|numeric|min:0.00001",
            "min_task_price" => "required|numeric|min:0|max:100|lte:max_task_price",
            "max_task_price" => "required|numeric|min:0|max:100|gte:min_task_price",
            "register_free" => "required|numeric|min:0",
            "member_commission" => "required|numeric|min:0.0001",
            "shop_status" => "required|numeric|min:0|max:1",
        ]);

        dc::first()->update([
            "min_cedit" => $request["min_cedit"],
            "min_balance" => $request["min_balance"],
            "commission_multiply" => $request["commission_multiply"],
            "min_task_price" => $request["min_task_price"],
            "max_task_price" => $request["max_task_price"],
            "register_free" => $request["register_free"],
            "member_commission" => $request["member_commission"],
            "shop_status" => $request["shop_status"],
        ]);

        return redirect("/admin/dealControl", ["data" => dc::first()]);
    }

    public function withdrawManage(){
        return view("admin.withdrawManage", ["withdraw_history" => withdrawal::get()]);
    }

    public function approve($id){
        withdrawal::where("id", $id)->update([
            "status" => 1
        ]);
        return redirect("/admin/withdrawManage")->with("success", "已同意提款");
    }

    public function reject($id){
        withdrawal::where("id", $id)->update([
            "status" => 2
        ]);
        return redirect("/admin/withdrawManage")->with("success", "已驳回提款");
    }

    public function addTasks(Request $request){
        $validation = Validator::make($request->all(), [
            "user_id" => "required|numeric|exists:users,id",
            "number" => "required|numeric",
            "productId1" => "required|numeric|exists:product,id",
            "productId2" => "required|numeric|exists:product,id",
            "productId3" => "required|numeric|exists:product,id"
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }

        $user = users::where("id", $request["user_id"])->first();
        for($i = 1; $i <= 3; $i++){
            $product = product::where('id', $request["productId{$i}"])->first();
            tq::create([
                "product_id" => $request["productId{$i}"],
                "user_id" => $request["user_id"],
                "deduction" => ($i === 1) ? 1 : 2,
                "commission" => round((($user->vip->commission * $product->price) * 100) / 100, 2)
            ]);
        }

        return response()->json("success", 200);
    }

    public function renewTask($id){
        users::where("id", $id)->update([
            "today_commission" => 0,
            "task_number" => 0
        ]);

        
        return redirect('/admin/memberList')->with("success", "已重置今天任务");
    }

    public function editCredit(Request $request){
        $validation = $request->validate([
            "credit" => "required|numeric|min:0|max:100",
            "user_id" => "required|numeric|exists:users,id"
        ]);

        users::where("id", $request["user_id"])->update([
            "credit" => $request["credit"]
        ]);

        return redirect('/admin/memberList')->with("success", "已调整信誉分");
    }

    public function registerView(){
        return view("admin.register");
    }

    public function addAdmin(Request $request){
        $validation = $request->validate([
            "name" => "required",
            "password" => "required",
        ]);

        users::create([
            "name" => $request->name,
            "password" => Hash::make($request->password),
            "role" => 0,
            "balance" => 0,
            "commission" => 0,
            "freeze_amount" => 0,
            "contact" => "",
            "gender" => 0,
            "withdrawal_pass" => "",
            "referral_code" => "",
            "in_percentage" => 0,
            "deal_status" => 0,
            "is_agent" => 0,
            "is_banned" => 0,
            "register_ip" => $request->ip(),
            "login_ip" => $request->ip(),
            "vip_id" => 1,
            "task_number" => 0,
            "today_commission" => 0,
            "total_commission" => 0,
            "team_commission" => 0,
            "credit" => 100,
            "withdrawal_number" => 0
        ]);

        return redirect("/admin/addAdmin")->with("success", "added");
    }
}
