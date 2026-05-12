<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyUsSetting extends Model
{
    use HasFactory;

    protected $table = 'why_us_settings';
    protected $fillable = ['key', 'value'];
}
