<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_whatsapp_number',
        'company_email',
        'company_name',
        'company_category',
        'brand',
        'product_name',
        'price',
        'stock',
        'volume',
        'category_id',
        'address',
        'description',
        'province_id',
        'city_id',
        'item_image'
    ];
}
