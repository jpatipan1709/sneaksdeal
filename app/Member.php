<?php



namespace App;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;



class Member extends Authenticatable

{

    protected $table = 'tb_member';
    protected $primaryKey = 'id_member';

    protected $fillable = [

        'username_member','name', 'email', 'facebook_id','google_id','name_member','lastname_member','tel_member','date_create_member','date_update_member','totken'

    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token',

    ];

}

