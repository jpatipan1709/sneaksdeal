<?php namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemAdminModel extends Model
{
    use SoftDeletes;

    protected $table = 'system_admin';
    protected $primaryKey = 'id_admin';
    protected $dates = ['deleted_at'];
}