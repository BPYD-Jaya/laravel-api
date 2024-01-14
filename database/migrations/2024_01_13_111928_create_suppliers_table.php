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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_whatsapp_number');
            $table->string('company_email');
            $table->string('company_name');
            $table->string('company_category');
            $table->string('brand');
            $table->string('product_name');
            $table->string('price');
            $table->integer('stock');
            $table->string('volume');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('address');
            $table->array('item_image');
            $table->text('description')->nullable();
            $table->foreignId('province_id')->constrained('provinces');
            $table->foreignId('city_id')->constrained('cities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
