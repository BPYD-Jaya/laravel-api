<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    // Jika terdapat relasi dengan model Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Jika terdapat relasi dengan model Province
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    // Jika terdapat relasi dengan model City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
