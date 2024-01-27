<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use Illuminate\Support\Facades\Hash;
use Auth;

class AdminController extends Controller
{

    public function index(Request $request){
        return view("");
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
            "role" => 0
        ]);

        return redirect("/admin/addAdmin")->with("success", "added");
    }
}
