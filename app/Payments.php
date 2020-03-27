<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'user_id', 'photo_id', 'concept', 'description', 'cost'
    ];
}
