<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permalink extends Model
{

    public $table = 'permalinks';

    public $timestamps = false;


    protected $fillable = [
        'id',
        'permalink'
    ];

    public function folder() {

        return $this->hasOne('App\Models\Folder', 'id');
    }
}
