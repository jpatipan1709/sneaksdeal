<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amphures extends Model
{
    protected $table = 'amphures';

    protected $fillable = [
        'zip_code','name_th', 'name_en', 'amphure_id'
    ];
}
