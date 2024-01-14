<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('product_name');
            $table->decimal('price');
            $table->integer('stock');
            $table->string('volume');
            $table->string('address');
            $table->array('item_image');
            $table->text('description');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('province_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->string('company_name');
            $table->string('company_category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
