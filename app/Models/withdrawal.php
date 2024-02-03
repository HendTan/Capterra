<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class withdrawal extends Model
{
    use HasFactory;

    protected $table = "withdrawal";

    protected $fillable = [
        "user_id",
        "withdrawal_id",
        "amount",
        "status"
    ];

    public function user(){
        return $this->belongsTo("App\Models\users");
    }

    public function withdrawal(){
        return $this->belongsTo("App\Models\withdrawal_info");
    }
}
