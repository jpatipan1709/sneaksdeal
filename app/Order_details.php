<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_details extends Model
{
    protected $table = 'tb_order_detail';
    // protected $primaryKey = 'order_id';

    protected $fillable = [
        'odt_id','order_id','main_id','voucher_id', 'qty', 'priceper','discount','total','created_at','updated_at'
    ];

    
}
