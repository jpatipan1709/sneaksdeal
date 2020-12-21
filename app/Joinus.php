<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Joinus extends Model
{
    protected $table = 'joinus';
    protected $primaryKey = 'ju_id';
    protected $fillable = [
        'ju_name','ju_hotel', 'ju_tel', 'ju_email','ju_content'
    ];
    // public $timestamps = false;
}
