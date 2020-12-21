<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainTestImport extends Model
{
    use SoftDeletes;
    protected $table = 'main_voucher_test_import';
    protected $primaryKey = 'id_main';
    protected $dates = ['deleted_at'];

}
