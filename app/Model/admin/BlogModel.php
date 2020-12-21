<?php

namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogModel extends Model
{
    use SoftDeletes;

    protected $table = 'tb_blog';
    protected $primaryKey = 'id_blog';
    protected $dates = ['deleted_at'];

}
