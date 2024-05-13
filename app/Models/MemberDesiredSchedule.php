<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MemberMultiAccountAlias;


class MemberDesiredSchedule extends Model
{

    public $table = 'desired_schedule';

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = true;

    public function getMemberDesiredSchedule($memberID)
    {

        $memberMultiAccountAlias = new MemberMultiAccountAlias();
        $selectedMultiAccounts = $memberMultiAccountAlias->getMemberSelectedAccounts($memberID);

        $ids = $selectedMultiAccounts->pluck('member_multi_account_id');  
                
        $desiredSchedules = MemberDesiredSchedule::where('member_id', $memberID)->where('valid', 1);

        if (count($ids) >= 1) { 
   
            $desiredSchedules->where(function ($query) use ($ids) {
                $query->whereIn('member_multi_account_id', $ids)
                    ->orWhereNull('member_multi_account_id');
            });

        } else {
            $desiredSchedules->where('member_multi_account_id', null);
        }

        return $desiredSchedules->orderBy('member_multi_account_id', 'ASC')->get();


    }

}
