<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Orders extends Model
{
    use SoftDeletes;
    protected $table = 'tb_order';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id','discount_id','status_order','mail_status','created_at', 'updated_at'
    ];
}
