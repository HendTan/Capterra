<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\users;
use App\Models\deal_control as qc;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private function generateReferralCode(){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $referralCode = '';

        for ($i = 0; $i < 6; $i++) {
            $referralCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $referralCode;
    }
    public function userLoginView(Request $request){

        $currentPath = url()->current();
        $pathName = parse_url($currentPath, PHP_URL_PATH);
        return view('user.login', ["title" => Str::ucfirst(substr($pathName,1))]);
    }

    public function register(Request $request){
        $validation = $request->validate([
            "username" => "required|min:6|max:20|unique:users,name",
            "phone" => "required|min:1|max:25",
            "password" => "required|min:8|max:16",
            "confirmPassword" => "required|min:8|max:16|same:password",
            "withdraw" => "required|min:8|max:16",
            "confirmWithdraw" => "required|min:8|max:16|same:withdraw",
            "referral" => "nullable|exists:user_info,referral_code",
            "maleRadio" => "required_without:femaleRadio",
            "femaleRadio" => "required_without:maleRadio"
        ]);

        $referralCode = "";

        while(true){
            $referralCode = $this->generateReferralCode();
            if(users::where("referral_code", $referralCode)->first() === null){
                break;
            }
        }

        users::create([
            "name" => $request->username,
            "password" => Hash::make($request->confirmPassword),
            "role" => 2,
            "contact" => $request->phone,
            "gender" => ($request->maleRadio === "on") ? 0 : 1,
            "withdrawal_pass" => Hash::make($request->confirmWithdraw),
            "referral_code" => $referralCode,
            "register_ip" => $request->ip(),
            "login_ip" => $request->ip(),
            "balance" => qc::first()->register_free,
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

    
        return redirect('/login')->with("success", "You have successfully registerd an account");
    }

    public function login(Request $request){
        $validation = $request->validate([
            "lusername" => "required|exists:users,name",
            "lpassword" => "required",
        ]);
        
        $user = users::where("name", $request["lusername"])->first();

        if( $user !== null && $user["is_banned"] === 1){
            error_log($user["is_banned"]);
            return redirect('/login')->with("fail", "You have been banned.");
        }

        if(Auth::attempt(['name' => $request->lusername, 'password' => $request->lpassword])){
            users::where("id", Auth::id())->update(["login_ip" => $request->ip()]);
            return redirect('/')->with("success", "You have logged in successfully.");
        }

        return redirect('/login')->with("lpassword", "The password is incorrect.");
    }

    public function logout(Request $request){
        if(Auth::check()){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect("/login")->with("success", "You have successfully logged out.");
        }

        return redirect("/login")->with("fail", "You are not logged in.");
    }

    public function adminLogout(request $request){
        if(Auth::check()){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect("/admin/login")->with("success", "You have successfully logged out.");
        }

        return redirect("/admin/login")->with("fail", "You are not logged in.");
    }

    public function adminLoginView(Request $request){
        // if(Auth::check()){
        //     Auth::logout();
        //     $request->session()->invalidate();
        //     $request->session()->regenerateToken();
        // }

        return view("admin.login");
    }


    public function adminLogin(Request $request){
        $validation = $request->validate([
            "name" => "required",
            "password" => "required",
        ]);

        if(Auth::attempt(["name" => $request->name, "password" => $request->password])){
            users::where("id", Auth::id())->update(["login_ip" => $request->ip()]);
            return redirect("/admin/login")->with("success", "login");
        }

        return redirect("/admin/login")->with("success", "failed");
    }
}
