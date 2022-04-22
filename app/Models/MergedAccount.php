<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MergedAccount extends Model
{
    public $table = 'merged_accounts';
    protected $guarded = array('created_at', 'updated_at');
}
