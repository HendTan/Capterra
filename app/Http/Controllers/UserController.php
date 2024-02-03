<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\Models\task_queue as tq;
use App\Models\deal_control as dc;
use App\Models\product;
use App\Models\customer_service;
use App\Models\withdrawal_info;
use App\Models\withdrawal;
use App\Models\transaction_history as th;

use Auth;
use Validator;

class UserController extends Controller
{
    public function index(Request $request){
        if(Auth::check()){
            return view("user.index");
        }

        return redirect("/login")->with("fail", "You have to login to view the page.");
    }

    public function start(Request $request){
        if(Auth::check()){
            $user = users::select("task_number", "total_commission", "today_commission", "team_commission")
            ->where("id", Auth::user()->id)->first();
            return view("user.start", ["user" => $user]);
        }

        return redirect("/login")->with("failed", "You have to login to view the page.");
    }

    public function getTask(Request $request){
        if(Auth::check()){
            $user = Auth::user();
            $dc = dc::first();
            if($user->balance < $dc->min_balance){
                $request->session()->flash('fail', "You must have at least $ {$dc->min_balance} to start a task.");
                return response()->json([], 200);
            }
            $task = tq::where("user_id", $user->id)->where("number", $user->task_number)->where("status", 2)->first();
            if($task === null){
                $product = product::where("price", ">=", $user->balance * ($dc->min_task_price / 100))
                ->where("price", "<=", $user->balance * ($dc->max_task_price / 100))
                ->get();

                if($product !== null && count($product) > 0){
                    $product = $product->random();
                }else{
                    $product = product::get()->random();
                }
                // $randNum = random_int(0, count($products));
                $task = tq::create([
                    "product_id" => $product->id,
                    "user_id" => $user->id,
                    "number" => $user->task_number,
                    "deduction" => 0,
                    "status" => 2,
                    "commission" => round((($user->vip->commission * $product->price) * 100) / 100, 2)
                ]);
            }
            $product = product::where("id", $task->product_id)->first();
            $data = [
                "task" => $task,
                "product" => $product
            ];
            return response()->json($data, 200);
        }

        return response()->json([], 200);
    }

    public function submitTask($id){
        if(!Auth::check()){
            return redirect('/login')->with("failed", "You have to login to access the page.");
        }

        $task = tq::where("id", $id)->first();

        if($task === null || $task->user_id !== Auth::id()){
            $request->session()->flash('fail', "Invalid id");
            return response()->json("/start");
        }

        $user = users::where("id", Auth::id())->first();

        if($task->deduction === 1){
            if($user->balance - $task->product->price >= 0){
                $task->status = 1;
                $commission = $task->commission;
                $user->balance += $commission;
                $user->total_commission += $commission;
                $user->today_commission += $commission;
                $user->task_number += 1;
    
                if($user->referral_id !== null){
                    $upper = users::where("id", $user->referral_id)->first();
                    $upperCommission = $commission * dc::first()->team_commission;
                    $upper->team_commission += $upperCommission;
                    $upper->balance += $upperCommission;
                    $upper->save();
                }
                $user->save();
                $task->save();
            }else{
                $user->balance -= $task->product->price;
                $user->freeze_amount = $task->product->price;
                $user->deal_status = 0;
                $user->save();
            }
        }else if($task->deduction === 2){
            $user->balance -= $user->balance * 2;
            $user->freeze_amount = $task->product->price;
            $user->deal_status = 0;
            $user->save();
        }else{
            $task->status = 1;
            $commission = $task->commission;
            $user->balance += $commission;
            $user->total_commission += $commission;
            $user->today_commission += $commission;
            $user->task_number += 1;

            if($user->referral_id !== null){
                $upper = users::where("id", $user->referral_id)->first();
                $upperCommission = $commission * dc::first()->team_commission;
                $upper->team_commission += $upperCommission;
                $upper->balance += $upperCommission;
                $upper->save();
            }
            $user->save();
            $task->save();
        }


        return response()->json('/record/3');
    }

    public function record($id){
        if(!Auth::check()){
            return redirect("/login")->with("failed", "You have to login to access the page");
        }

        if(is_numeric($id) && $id >= "0" && $id <= "2"){
            $tasks = tq::where("user_id", Auth::id())->where("status", $id)->get();
            return view("user.record", ["data" => $tasks]);
        }else if(is_numeric($id) && $id === "3"){
            $tasks = tq::where("user_id", Auth::id())->get();
            return view("user.record", ["data" => $tasks]);
        }else{
            return redirect("/")->with("fail", "Invalid Record Id");
        }
    }

    public function service(){
       if(Auth::check()){
            return view("user.service", ["data" => customer_service::get()]);  
       }

       return redirect("/login")->with("failed", "You have to login to access the page.");
    }

    public function profile(){
        if(Auth::check()){
            return view("user.profile", [
                "data" => users::where("id", Auth::id())->first(),
                "withdraw_history" => withdrawal::where("user_id", Auth::id())->get(),
                "deposit" => th::where("user_id", Auth::id())->where("type", 0)->get()
            ]);
        }

        return redirect("/login")->with("failed", "You have to login to access the page.");
    }

    public function submitWithdrawInfo(Request $request){
        $info = withdrawal_info::where("user_id", Auth::id())->first();
        if($info === null){
            withdrawal_info::create([
                "user_id" => Auth::id(),
                "address" => $request["address"],
                "first_name" => $request["firstName"],
                "last_name" => $request["lastName"],
                "card_num" => $request["cardNum"],
                "city" => $request["city"],
                "name" => $request["name"],
                "email" => $request["email"],
                "contact" => $request["contact"],
                "type" => $request["type"],
            ]);
        }else{
            withdrawal_info::where("user_id", Auth::id())->update([
                "address" => $request["address"],
                "first_name" => $request["firstName"],
                "last_name" => $request["lastName"],
                "card_num" => $request["cardNum"],
                "city" => $request["city"],
                "name" => $request["name"],
                "email" => $request["email"],
                "contact" => $request["contact"],
                "type" => $request["type"],
            ]);
        }

        $request->session()->flash('success', "You have successfully save your withdrawal information.");
        return response()->json("success", 200);
    }

    public function submitWithdraw(Request $request){
        $validation = Validator::make($request->all(), [
            "amount" => "required|numeric|min:" . Auth::user()->vip->min_withdraw . "|max:". Auth::user()->vip->min_withdraw,
            "password" => "required|min:8|max:16"
        ]);

        if($validation->fails()){
            return response()->json(["errors" => $validation->errors()], 200);
        }
        if(Auth::user()->task_number < Auth::user()->vip->task_number){
            $request->session()->flash('fail', "You have to complete " . Auth::user()->vip->task_number . "before withdraw.");
            return response()->json([], 200);
        }

        if(Auth::user()->withdrawal_number > Auth::user()->vip->withdraw_number){
            $request->session()->flash('fail', "You have withdrawn more than " . Auth::user()->vip->withdraw_number ." time(s).");
            return response()->json([], 200);
        }

        

        withdrawal::create([
            "user_id" => Auth::id(),
            "withdrawal_id" => withdrawal_info::where("user_id", Auth::id())->first()->id,
            "amount" => $request["amount"],
            "status" => 2
        ]);

        $request->session()->flash('success', "You have successfully submit a withdraw request.");
        return response()->json("success", 200);
    }
}
