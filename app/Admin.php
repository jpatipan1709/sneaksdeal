<?php

namespace App;

use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;



class Admin extends Authenticatable

{

    use Notifiable;

    protected  $guard ='admin';
    protected $table = 'system_admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = [
        'name_admin', 'email', 'password'
    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token',

    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
}