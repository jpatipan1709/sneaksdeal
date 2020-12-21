<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherTestImport extends Model
{
    use SoftDeletes;
    protected $table = 'tb_voucher_test_import';
    protected $primaryKey = 'voucher_id';
    protected $dates = ['deleted_at'];

}
