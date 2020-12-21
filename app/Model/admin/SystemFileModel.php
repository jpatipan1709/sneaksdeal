<?php namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;

class SystemFileModel extends Model
{
    protected $table = 'system_file';

//    protected $fillable = ['relationId', 'relationTable', 'name', 'sort_img'];
    public $timestamps = false;
}