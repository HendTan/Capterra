<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction_history extends Model
{
    use HasFactory;

    protected $table = "transcation_history";

    protected $fillable = [
        "user_id",
        "type",
        "account",
        "th_number",
        "amount"
    ];
}
