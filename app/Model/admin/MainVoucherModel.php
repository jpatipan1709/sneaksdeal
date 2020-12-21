<?php

namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainVoucherModel extends Model
{
    use SoftDeletes;
    protected $table = 'main_voucher';
    protected $primaryKey = 'id_main';
    protected $dates = ['deleted_at'];

}
