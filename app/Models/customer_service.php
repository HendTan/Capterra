<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_service extends Model
{
    use HasFactory;

    protected $table = "customer_service";

    protected $fillable = [
        "name",
        "contact",
        "link",
        "type"
    ];
}
