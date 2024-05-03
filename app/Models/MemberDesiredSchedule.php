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
        

       

        return MemberDesiredSchedule::where('member_id', $memberID)
            ->where('valid', 1)
            ->whereIn('member_multi_account_id', $ids)
            ->orderBy('member_multi_account_id', 'ASC')
            ->get();
    }

}
