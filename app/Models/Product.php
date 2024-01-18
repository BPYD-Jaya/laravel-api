<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable=[
        'brand',
        'product_name',
        'price',
        'stock',
        'volume',
        'address',
        'item_image',
        'description',
        'category_id',
        'province_id',
        'city_id',
        'company_name',
        'company_category',
        'company_whatsapp_number',
        'storage_type',
        'packaging',
        'additional_info'
    ];

    protected $casts = [
        'additional_info' => 'array'
    ];
}
