<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Travelguide extends Model
{
    protected $table = 'travel_guides';
    protected $primaryKey = 'tg_id';
    protected $fillable = [
        'tg_name'
    ];
}
