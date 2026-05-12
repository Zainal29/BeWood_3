<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyUsItem extends Model
{
    use HasFactory;

    protected $table = 'why_us_items';
    protected $fillable = ['title', 'description', 'icon', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
