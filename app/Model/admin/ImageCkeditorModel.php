<?php
namespace App\Model\admin;

use Illuminate\Database\Eloquent\Model;

class ImageCkeditorModel extends Model
{
    protected $table = 'image_ckeditor';

    protected $fillable = [
        'type_menu', 'name_img','ref_id_album'
    ];

//    public $timestamps = 'false';

}