<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deal_control extends Model
{
    use HasFactory;

    protected $table = "deal_control";

    protected $fillable = [
        "min_cedit",
        "min_balance",
        "commission_multiply",
        "min_task_price",
        "max_task_price",
        "register_free",
        "member_commission",
        "shop_status",
    ];
}
