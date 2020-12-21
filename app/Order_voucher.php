<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_voucher extends Model
{
    protected $table = 'order_vouchers';
    // protected $primaryKey = 'order_detail_id';
    protected $fillable = [
        'orders_id','order_detail_id', 'code_voucher', 'stat_voucher','code_confirm'
    ];
    public $timestamps = false;
}
