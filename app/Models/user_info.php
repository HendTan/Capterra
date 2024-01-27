<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_info extends Model
{
    use HasFactory;

    protected $table = "user_info";

    protected $fillable = [
        "user_id",
        "contact",
        "gender",
        "withdrawal_pass",
        "referral_code",
        "register_ip",
        "login_ip"
    ];
}
