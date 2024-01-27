<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\users;
use App\Models\user_info;
use App\Models\user_finance;

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
        return view('user.login', ["title" => substr($pathName,1)]);
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

        $newUserID = users::create([
            "name" => $request->username,
            "password" => Hash::make($request->confirmPassword),
            "role" => 2
        ])->id;

        $referralCode = "";

        while(true){
            $referralCode = $this->generateReferralCode();
            if(user_info::where("referral_code", $referralCode)->first() === null){
                break;
            }
        }

        user_info::create([
            "user_id" => $newUserID,
            "contact" => $request->phone,
            "gender" => ($request->maleRadio === "on") ? 0 : 1,
            "withdrawal_pass" => Hash::make($request->confirmWithdraw),
            "referral_code" => $referralCode,
            "register_ip" => $request->ip(),
            "login_ip" => $request->ip()
        ]);

        user_finance::create([
            "user_id" => $newUserID,
            "balance" => 70,
            "commission" => 0,
            "freeze_amount" => 0
        ]);

        return redirect('/login')->with("success", "You have successfully registerd an account");
    }

    public function login(Request $request){
        $validation = $request->validate([
            "lusername" => "required|exists:users,name",
            "lpassword" => "required",
        ]);

        if(Auth::attempt(['name' => $request->lusername, 'password' => $request->lpassword])){
            user_info::where("id", Auth::id())->update(["login_ip" => $request->ip()]);
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

    public function adminLoginView(Request $request){
        if(Auth::check()){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view("admin.login");
    }


    public function adminLogin(Request $request){
        $validation = $request->validate([
            "name" => "required",
            "password" => "required",
        ]);

        if(Auth::attempt(["name" => $request->name, "password" => $request->password])){
            return redirect("/admin/login")->with("success", "login");
        }

        return redirect("/admin/login")->with("success", "failed");
    }
}
