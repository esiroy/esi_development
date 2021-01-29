<?php

namespace App\Models;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class UserImporter extends Authenticatable
{
    public $table = 'users';


    use SoftDeletes, Notifiable, HasApiTokens;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $guarded = array('created_at', 'updated_at');

    /*
    protected $fillable = [
        'first_name', 
        'last_name', 
        'username', 
        'email', 
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'api_token',
        'email_verified_at',
    ];*/


}
