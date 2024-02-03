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
        Schema::create("vip", function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->float("price");
            $table->float("withdraw_fee");
            $table->double("commission",15,8);
            $table->float("min_balance");
            $table->integer("min_task_number");
            $table->integer("task_number");
            $table->double("min_withdraw",20,8);
            $table->float("max_withdraw",20,8);
            $table->double("withdraw_number",20,8);
            $table->string("img_path");
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("password");
            $table->tinyInteger('role');
            $table->string("contact");
            $table->tinyInteger('gender');
            $table->string("withdrawal_pass");
            $table->string("referral_code");
            $table->unsignedBigInteger("referral_id")->nullable();
            $table->foreign("referral_id")->references("id")->on("users");
            $table->string("register_ip");
            $table->string("login_ip");
            $table->float("balance");
            $table->float("commission");
            $table->float("freeze_amount");
            $table->integer("in_percentage");
            $table->tinyInteger("deal_status");
            $table->tinyInteger("is_agent");
            $table->tinyInteger("is_banned");
            $table->unsignedBigInteger("vip_id");
            $table->foreign("vip_id")->references("id")->on("vip");
            $table->integer("task_number");
            $table->double("today_commission",20,8);
            $table->double("total_commission",20,8);
            $table->double("team_commission",20,8);
            $table->integer("withdrawal_number");
            $table->integer("credit");
            $table->timestamps();
        });

        Schema::create('transcation_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->tinyInteger("type");
            $table->string("account");
            $table->string("th_number");
            $table->float("amount");
            $table->timestamps();
        });

        Schema::create("product", function(Blueprint $table){
            $table->id();
            $table->string("name");
            $table->float("price");
            $table->string("img_path");
            $table->timestamps();
        });

        Schema::create("task_queue", function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->foreign("product_id")->references("id")->on("product");
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->integer("number");
            $table->tinyInteger("deduction");
            $table->double("commission",20,8);
            $table->tinyInteger("status");
            $table->timestamps();
        });

        Schema::create("deal_control", function(Blueprint $table){
            $table->id();
            $table->integer("min_cedit");
            $table->double("min_balance",20,8);
            $table->integer("commission_multiply");
            $table->integer("min_task_price");
            $table->integer("max_task_price");
            $table->double("register_free",20,8);
            $table->double("member_commission", 20,8);
            $table->tinyInteger("shop_status");
            $table->timestamps();
        });

        Schema::create('customer_service', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("contact");
            $table->string("link");
            $table->tinyInteger("type");
            
            $table->timestamps();
        });

        Schema::create('withdrawal_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->string("address")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("card_num")->nullable();
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("contact")->nullable();
            $table->tinyInteger("type");
            $table->timestamps();
        });

        Schema::create('withdrawal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("withdrawal_id");
            $table->foreign("withdrawal_id")->references("id")->on("withdrawal_info");
            $table->double("amount", 20,8);
            $table->tinyInteger("status");
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
