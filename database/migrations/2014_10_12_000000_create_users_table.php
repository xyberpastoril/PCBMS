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
            $table->string('name');
            $table->string('email')->unique(); // TODO: Remove this later
            $table->string('username')->unique();
            $table->enum('designation', [
                'manager',
                'cashier'
            ])->default('cashier');
            $table->timestamp('email_verified_at')->nullable(); // TODO: Remove this later
            $table->string('password');
            $table->rememberToken(); // TODO: Remove this later
            $table->timestamps(); // TODO: Remove updated_at
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
