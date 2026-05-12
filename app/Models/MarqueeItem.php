<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarqueeItem extends Model
{
    use HasFactory;

    protected $table = 'marquee_items';
    protected $fillable = ['text', 'icon', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'order' => 'integer'];
}