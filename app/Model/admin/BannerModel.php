<?php

namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;

class BannerModel extends Model
{
    protected $table = 'tb_banner';
    protected $fillable = [
        'name_banner', 'link_banner',
    ];


    public $timestamps = 'false';

}
