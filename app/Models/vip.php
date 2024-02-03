<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vip extends Model
{
    use HasFactory;

    protected $table ="vip";

    protected $fillable = [
        "name",
        "price",
        "commission",
        "min_balance",
        "task_number",
        "min_withdraw",
        "max_withdraw",
        "withdraw_fee",
        "min_task_number",
        "withdraw_number",
        "img_path",
    ];

    public function vip(){
        $this->hasMany("App\Model\users");
    }
}
