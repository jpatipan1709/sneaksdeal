<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $table = 'provinces';

    protected $fillable = [
        'zip_code','name_th', 'name_en', 'amphure_id'
    ];
}
