<?php

namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;

class FacilitiesModel extends Model
{
    protected $table = 'tb_facilities';
    protected $fillable = [
        'name_facilities', 'icon_facilities',
    ];


    public $timestamps = 'false';

}
