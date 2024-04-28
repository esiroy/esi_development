<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberMultiAccount extends Model
{
   
    public $table = 'member_multi_account';

    protected $guarded = array();

    public function getAccounts() {

        return $this->where('valid', 1)->orderBy('sequence_number', 'ASC')->get();
    }
}
