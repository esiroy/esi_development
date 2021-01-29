<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSupport extends Model
{
    public $table = 'customer_support';

    protected $guarded = array('created_at', 'updated_at');
}
