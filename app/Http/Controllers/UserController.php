<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    public function index(Request $request){
        if(Auth::check()){
            return view("user.index");
        }

        return redirect("/login")->with("fail", "You have to login to view the page.");
    }
}
