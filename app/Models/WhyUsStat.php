<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyUsStat extends Model
{
    use HasFactory;

    protected $table = 'why_us_stats';
    protected $fillable = ['label', 'value', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
