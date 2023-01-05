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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('organization_id');
            $table->string('product');
            $table->unsignedInteger('category_id');
            $table->string('product_type');
            $table->string('product_code')->nullable();
            $table->text('barcodes')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('selling_price');
            $table->string('stocks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
