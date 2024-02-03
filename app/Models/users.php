<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        "role",
        "balance",
        "commission",
        "freeze_amount",
        "contact",
        "gender",
        "withdrawal_pass",
        "referral_code",
        "register_ip",
        "login_ip",
        "in_percentage",
        "deal_status",
        "is_agent",
        "is_banned",
        "vip_id",
        "task_number",
        "today_commission",
        "total_commission",
        "team_commission",
        "credit",
        "withdrawal_number"
    ];

    public function referral(){
        return $this->belongsTo("App\Models\users");
    }

    public function vip(){
        return $this->belongsTo("App\Models\\vip");
    }

    public function product(){
        return $this->hasMany("App\Models\\task_queue");
    }

    public function user(){
        return $this->hasMany("App\Models\withdrawal");
    }
}
