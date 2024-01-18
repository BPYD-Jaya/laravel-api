<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'address',
        'phone_company',
        'email_company',
        'ig_link',
        'fb_link',
        'wa_link'
    ];
}
