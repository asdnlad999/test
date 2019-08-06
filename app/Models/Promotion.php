<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $guarded = [];
    protected $casts = [
        'user_ids' => 'array'
    ];
    public function user(){
        $user_names = User::whereIn('id',$this->user_ids)->pluck('name')->toArray();
        return implode('|',$user_names);
    }

}
