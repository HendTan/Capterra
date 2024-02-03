<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task_queue extends Model
{
    use HasFactory;

    protected $table = "task_queue";
    protected $fillable = [
        "product_id",
        "user_id",
        "number",
        "deduction",
        "status",
        "commission"
    ];

    public function product(){
        return $this->belongsTo("App\Models\product");
    }
    
}
