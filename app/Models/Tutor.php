<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    public $table = 'tutors';

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = false;    

    public function setBirthdateAttribute($value)
    {       
        $this->attributes['birthdate'] = date('Y-m-d', strtotime($value)); 
    }
    
    public function user() 
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }



}