<?php

namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;

class SelectVoucherModel extends Model
{
    protected $table = 'select_voucher';
    protected $primaryKey = 'select_id';
    protected $fillable = [	'main_join'	,'voucher_id_join',	'sort_by_view'];



}
