<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionPage extends Model
{
    protected $guarded = [];
    protected $casts = [
        'pictures'=>'array'
    ];
}
