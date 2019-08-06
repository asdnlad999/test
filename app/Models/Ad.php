<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class)->withDefault(['name'=>'未知']);
    }
}
