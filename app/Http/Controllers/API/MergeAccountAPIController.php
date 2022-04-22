<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, Hash;
use App\Models\User;
use App\Models\MergedAccount;

class MergeAccountAPIController extends Controller
{

    public function getType(Request $request) {
    
        $memberID = $request->member_id;
      
        //Test if you have merge your account
        $mergedAccount = MergedAccount::select('users.id', 'users.email', 'merged_accounts.created_at as date')
                        ->where('merged_member_id', $memberID )
                        ->leftJoin('users', 'users.id', '=', 'merged_accounts.merged_member_id')
                        ->first();

        if ($mergedAccount) {

            $accountType = "secondary";

            return Response()->json([
                "success"       => true,
                "message"       => "Account is secondary",
                "type"          => "secondary",
                "main_account"  => $mergedAccount
            ]);

        } else {
        
            return Response()->json([
                "success"           => false,
                "message"           => "Account is a main account",
                "type"              => "main"
            ]);        
        
        }
    
    }


    public function get(Request $request) 
    {
        $memberID = $request->member_id;

        $mergedAccounts = MergedAccount::select('users.id', 'users.email', 'merged_accounts.created_at as date')
                ->where('member_id', $memberID)
                ->leftJoin('users', 'users.id', '=', 'merged_accounts.merged_member_id')
                ->get();
        
        if ($mergedAccounts) {
        
            return Response()->json([
                            "success"           => true,
                            "message"           => "users has been successfully found.",
                            "merged_accounts"   => $mergedAccounts
                        ]);

        } else {

            return Response()->json([
                "success"           => false,
                "message"           => "Sorry, Account can't be merged right now.",
            ]);
        }
    }




    public function store(Request $request) {

        //remove first (1) since we added 1
        $tempID = $request->member_id;

        $memberID = substr($tempID, 1);
        $password = $request->password;

       

        //Test if others have merge your account
        $mainAccounts = MergedAccount::where('member_id', $memberID )->first();

        if ($mainAccounts) 
        {
            return Response()->json([
                "success"           => false,
                "message"           => "Sorry, Account ID ". $memberID ." has already been assigned as a main account",
            ]);        
        }


        //Test if others have merge your account
        $otherAccounts = MergedAccount::where('member_id', '!=', Auth::user()->id)
                ->where('merged_member_id', $memberID )
                ->first();

        if ($otherAccounts) 
        {
            return Response()->json([
                "success"           => false,
                "message"           => "Other account has already merged this account",
            ]);        
        }

        //Test if you have merge your account
        $mergedAccount = MergedAccount::where('member_id', Auth::user()->id)
                ->where('merged_member_id', $memberID )
                ->first();
                

        if (!$mergedAccount) 
        {
            $user = User::where('id', $memberID)->first();

            if ($user) {

                $validCredentials = Hash::check($password, $user->password);

                if ($validCredentials) {

                    if ($memberID == Auth::user()->id) 
                    {                
                        return Response()->json([
                            "success"           => false,
                            "message"           => "You can't merge your own account.",    
                            
                        ]);        
                    }

                    if (isset($request->owner_id)) {
                        $owner_member_id = $request->owner_id;
                    } else {
                        $owner_member_id = Auth::user()->id;
                    }


                    $mergedCreated = MergedAccount::create([
                        'member_id' => $owner_member_id,
                        'merged_member_id' => $memberID
                    ]);

                    if ($mergedCreated) {
                    
                        return Response()->json([
                            "success"       => true,
                            "message"       => "user has been successfully found and successfully merged",
                        ]);

                    } else {
        
                        return Response()->json([
                            "success"           => false,
                            "message"           => "Sorry, Account can't be merged right now.",
                        ]);
                    }

                } else {
                
                    return Response()->json([
                        "success"       => true,
                        "message"       => "Sorry, user account can't be merged to your account",
                    ]); 
                }

            } else {

                return Response()->json([
                    "success"           => false,
                    "message"           => "Sorry, User account not found please check again later",
                ]);
            
            }
        } else {
        
                return Response()->json([
                    "success"           => false,
                    "message"           => "Sorry, Account is already merged with your account",    
                   
                ]);
        }
    
    }


  public function destroy(Request $request) {


        if (isset($request->owner_id)) {
            $owner_member_id = $request->owner_id;
        } else {
            $owner_member_id = Auth::user()->id;
        }

        $delete = MergedAccount::where('member_id', $owner_member_id)
                ->where('merged_member_id', $request->member_id)
                ->delete();
                
        if ($delete) {

            $mergedAccounts = MergedAccount::select('users.id', 'users.email', 'merged_accounts.created_at as date')
            ->where('member_id', $request->member_id)
            ->leftJoin('users', 'users.id', '=', 'merged_accounts.merged_member_id')
            ->get();
        
            return Response()->json([
                            "success"           => true,
                            "message"           => "user account has been successfully removed.",
                            "merged_accounts"   => $mergedAccounts
                        ]);

        } else {

            return Response()->json([
                "success"           => false,
                "message"           => "Sorry, Account can't be remove right now.",
            ]);
        }

    }    
}
