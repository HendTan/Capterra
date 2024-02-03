<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class withdrawal_info extends Model
{
    use HasFactory;

    protected $table = "withdrawal_info";

    protected $fillable = [
        "user_id",
        "address",
        "first_name",
        "last_name",
        "card_num",
        "city",
        "name",
        "email",
        "contact",
        "type"
    ];

    public function withdrawal(){
        return $this->hasMany("App\Models\withdrawal");
    }
}
