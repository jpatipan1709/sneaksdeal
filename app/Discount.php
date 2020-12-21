<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
class Discount extends Model
{
    // use SoftDeletes;
    protected $primaryKey = 'discount_id';
    protected $table = 'tb_discount';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'discount_code','discount_qty','discount_min', 'date_start', 'date_end','discount_bath','discount_main',
    ];

 
}
