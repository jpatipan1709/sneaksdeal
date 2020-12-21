<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    protected $table = 'districts';

    protected $fillable = [
        'code','name_th', 'name_en', 'province_id'
    ];
}
