<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->string("contact");
            $table->tinyInteger('gender');
            $table->string("withdrawal_pass");
            $table->string("referral_code");
            $table->unsignedBigInteger("referral_id")->nullable();
            $table->foreign("referral_id")->references("id")->on("users");
            $table->string("register_ip");
            $table->string("login_ip");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infor');
    }
};
