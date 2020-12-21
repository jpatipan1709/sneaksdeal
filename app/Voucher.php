<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = "tb_voucher";
    protected $primaryKey = 'voucher_id';
    protected $fillable = [
        'voucher_id','relation_mainid', 'name_voucher', 'relation_facilityid','stat_sale','detail_stat_sale','qty_customer','qty_night','date_open','date_close','qty_voucher','price_agent','sale','price_sale','title_voucher','term_voucher','link_vdo'
    ];
}
