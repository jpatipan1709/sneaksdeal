<?php

namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherModel extends Model
{
    use SoftDeletes;
    protected $table = 'tb_voucher';
    protected $primaryKey = 'voucher_id';
    protected $dates = ['deleted_at'];

}
