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
        Schema::create('consigned_products', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('consign_order_id')->nullable()->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('particulars');
            $table->date('expiration_date')->nullable();
            $table->float('unit_price')->nullable();
            $table->float('sale_price')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('quantity_paid')->default(0);
            $table->integer('quantity_returned')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consigned_products');
    }
};
