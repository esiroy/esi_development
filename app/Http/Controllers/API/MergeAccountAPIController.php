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
      

        //is Main Account?
        $isMainMerged = MergedAccount::select('users.id', 'users.email', 'merged_accounts.member_id', 'merged_accounts.created_at as date')
                        ->where('member_id', $memberID )
                        ->leftJoin('users', 'users.id', '=', 'merged_accounts.merged_member_id')
                        ->first();

        if ($isMainMerged) {
            $linkedMainAccount = true;            
        } else {
            $linkedMainAccount = false;
        }

        //Test if you have merge your account
        $mergedAccount = MergedAccount::select('users.id', 'users.email', 'merged_accounts.member_id', 'merged_accounts.created_at as date')
                        ->where('merged_member_id', $memberID )
                        ->leftJoin('users', 'users.id', '=', 'merged_accounts.merged_member_id')
                        ->first();
                        
        if ($mergedAccount) {

            $accountType = "secondary";

            $mainAccount = User::select('users.id', 'users.email')->find( $mergedAccount->member_id );

            return Response()->json([
                "success"       => true,
                "message"       => "Account is secondary",
                "type"          => "secondary",
                "main_account"  => $mainAccount
            ]);

        } else {

            if ($linkedMainAccount) {
                $type = "main";
            } else {
                $type = "unlinked";
            }
        
            return Response()->json([
                "success"           => false,
                "message"           => "Account is a main account",
                "type"              => $type
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




    public function store(Request $request) 
    {

        //remove first (1) since we added 1
        $tempID = $request->member_id;

        //trim first number which is 1
        $memberID = substr($tempID, 1);
        $password = $request->password;

        //or try to search if this is an email
        $email = $request->member_id;

        $user = User::where('id', $memberID)->orWhere('email', $email )->first();

        if (!$user) {
            return Response()->json([
                "success"           => false,
                "message"           => "Sorry, User account not found please check again later",
            ]);
        }

        if (isset($request->owner_id)) {
            $owner_member_id = $request->owner_id;
        } else {
            $owner_member_id = Auth::user()->id;
        }


        //Test if others have merge your account
        $mainAccounts = MergedAccount::where('member_id', $user->id )->first();

        if ($mainAccounts) 
        {
            return Response()->json([
                "type"              => "main",
                "success"           => false,
                "message"           => "Account ID 1$user->id with an email address of $user->email has already been assigned as a main account, do you want to link as a merged account instead?",
            ]);        
        }


        //Test if others have merge your account
        $otherAccounts = MergedAccount::where('member_id', '!=', $owner_member_id )
                ->where('merged_member_id', $user->id)
                ->first();

        if ($otherAccounts) 
        {
            return Response()->json([
                "success"           => false,
                "message"           => "Other account has already merged this account",
            ]);        
        }

        //Test if you have merge your account
        $mergedAccount = MergedAccount::where('member_id', $owner_member_id)
                ->where('merged_member_id', $user->id )
                ->first();
                

        if (!$mergedAccount) 
        {
            if ($user) {

                $validCredentials = Hash::check($password, $user->password);

                if ($validCredentials) 
                {
                    if ($user->id == $owner_member_id) 
                    {                
                        return Response()->json([
                            "success"           => false,
                            "message"           => "You can't merge your own account.",    
                            
                        ]);        
                    }

                    $mergedCreated = MergedAccount::create([
                        'member_id' => $owner_member_id,
                        'merged_member_id' =>  $user->id
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
                        "message"       => "Sorry, we can't verify that you are the owner of this account.",
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


    public function mergeSecondaryToMain(Request $request) {
    

        //remove first (1) since we added 1
        $tempID = $request->member_id;

        //trim first number which is 1
        $memberID = substr($tempID, 1);
        $password = $request->password;

        //or try to search if this is an email
        $email = $request->member_id;

        $user = User::where('id', $memberID)->orWhere('email', $email )->first();

        if (isset($request->owner_id)) {
            $owner_member_id = $request->owner_id;
        } else {
            $owner_member_id = Auth::user()->id;
        }

        $mainAccount = MergedAccount::where('member_id', $user->id )->first();

        if ($mainAccount) 
        {
            $merged = MergedAccount::create([
                'member_id'         => $user->id,
                'merged_member_id'  => $owner_member_id,
            ]);

            if ($merged) {

                return Response()->json([
                    "success"           => true,
                    "message"           => "Successfully linked to main account",
                ]);  

            } else {
            
                return Response()->json([
                    "success"           => true,
                    "message"           => "We can't link your account due to an error",
                ]);              
            }

        }
    }

    public function adminMergedAccount(Request $request) {

        //remove first (1) since we added 1
        $tempID = $request->member_id;
        $memberID = substr($tempID, 1);
     

        if (isset($request->owner_id)) {
            $owner_member_id = $request->owner_id;
        } else {
            $owner_member_id = Auth::user()->id;
        }


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

              
                if ($memberID == $owner_member_id) 
                {                
                    return Response()->json([
                        "success"           => false,
                        "message"           => "Users can't merge their your own account.",    
                        
                    ]);        
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
                    "message"       => "Sorry, we can't find any account with your member ID.",
                ]); 
            }

         
        } else {
        
                return Response()->json([
                    "success"           => false,
                    "message"           => "Sorry, Account is already merged with your account",    
                   
                ]);
        }
    
    }     
}