<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'company_phone',
        'company_email',
        'company_name',
        'product',
        'price',
        'stock',
        'unit',
        'category_id',
        'location',
        'image',
        'description'
    ];
}
