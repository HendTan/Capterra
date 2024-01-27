<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_finance extends Model
{
    use HasFactory;

    protected $table = "user_finance";

    protected $fillable = [
        "user_id",
        "balance",
        "commission",
        "freeze_amount"
    ];
}
