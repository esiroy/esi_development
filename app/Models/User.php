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

class User extends Authenticatable implements MustVerifyEmail
{
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

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;

    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;

    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));

    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);

    }

    public function folders()
    {
        return $this->belongsToMany(Folder::class);
    }
    
    //members
    public function members()
    {
        return $this->belongsToMany(Member::class);
    }    

    public function memberInfo() 
    {
        return $this->hasOne('App\Models\Member');
    }

    //tutors
    public function tutors()
    {
        return $this->belongsToMany(Tutor::class);
    }

    public function tutorInfo() 
    {
        return $this->hasOne('App\Models\Tutor');
    }

    //Managers
    public function managers()
    {
        return $this->belongsToMany(Manager::class);
    } 
    
    public function managerInfo() 
    {
        return $this->hasOne('App\Models\Manager');
    }

    //Agent
    public function agent() 
    {
        return $this->hasOne('App\Models\Agent');
    }

    //Agents
    public function agents()
    {
        return $this->hasOne(Agent::class);
    } 
    
    public function agentInfo() 
    {
        return $this->hasOne('App\Models\Agent');
    }    


    public function mergedAccounts() {
    
        return $this->hasMany(MergedAccount::class, 'member_id', 'id');
    } 

    public function memberSettings()
    {
        return $this->hasMany(MemberSetting::class, 'user_id');
    }    
}
