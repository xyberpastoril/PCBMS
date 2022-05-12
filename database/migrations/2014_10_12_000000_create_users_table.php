<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->enum('designation', [
                'manager',
                'cashier'
            ])->default('cashier');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken(); // TODO: Remove this later
            $table->timestamp('created_at')->default(now());
            $table->timestamp('password_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
