<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\File;
use Auth, DB, Str, Gate;




class File extends Model
{
    
    protected $guarded = array('created_at', 'updated_at');

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function users() 
    {
        return $this->belongsToMany(User::class);
    }

    public function getSharedAttribute()
    {
        return $this->getPermittedUsers($this->id);
    }


    public static function getPermittedUsers($fileID) 
    {
       //Look for the permitted users of this folder
       $shared = [];
       $file = File::find($fileID);

       //File has multiple owners, let us find this owners
       $sharedFileUserFiles = $file->users;

       foreach ($sharedFileUserFiles as $sharedFileUserFile) 
       {
           $shared[] = [
                        'id'    => $sharedFileUserFile->id,
                        'code'  => $sharedFileUserFile->id,
                        'name'  => $sharedFileUserFile->name,
                        ];
       }

       return $shared;
    }

    /** 
    * Determine if users is permitted to view the pagess
    * @var fileID
    * @return Array $response  true - successs, false - failed, message : failed message
    */
    public static function canView($id) 
    {
        $file  = File::find($id);

        if (isset($file)) 
        {
            $folder = Folder::find($file->folder_id);

            if (isset($folder)) 
            {
                if (strtolower($file->privacy) == 'public' || strtolower($folder->privacy) == 'public') 
                {
                    $response = ['success' => true, 'file' => $file];
                } 
                else if (Auth::check()) 
                {
                    $user   = Auth::user();
                    
                    if (Folder::hasPermission($user->id,  $folder->id) == false && File::hasPermission($user->id, $file->id) == false)
                    {
                        $response = ['success' => false, 'message' => "You have no permission to view this file"];
                    }
                    else 
                    {
                        $response = ['success' => true, 'file' => $file];
                    }
                } 
                else 
                {
                    if ($folder->privacy == 'public') 
                    {
                        $response = ['success' => true, 'file' => $file];

                    } else {
                        $response = ['success' => false, 'message' => "You must be logged in to view this file"];
                    }
                }
            } else {
                $response = ['success' => false, 'message' => "Folder not found"];
            }
        } 
        else 
        {
            $response = ['success' => false, 'message' => "File is not found"];   
        }

        return (object) $response;
    }


    /** 
    * Determine if users is permitted to view this file
    * @var userID   
    * @var fileID
    * @var returns  Boolen 
    */
    public static function hasPermission($userID, $fileID) 
    {
        //Look for the permitted users of this file
        $usersSharedTo = [];
        $file = File::find($fileID);

        //file has multiple owners find this owners
        $fileUsers = $file->users;
        foreach ($fileUsers as $fileUser) 
        {
            $usersSharedTo[] = $fileUser->id;
        }

        //return true it has permission, false for no permssion
        if (array_intersect([$userID], $usersSharedTo) || $userID == $file->user_id) 
        {
            return true;

        } else {

            return false;
        }
    }

    public static function getLink($id) {
        return url("file/$id");
    }

    public static function reorder($files) {    

        DB::beginTransaction();

        try {           

            $loop = 1;

            foreach ($files as $file) {
                
                File::where('id', $file['id'])->update(['order_id' => $loop]);

                $loop ++;
            }
           

            DB::commit();


            return (object) [
                'response' => true,
                'message'  => "File order has been updated successfully."
            ];


        } catch (\Exception $e) {

            DB::rollBack();

            return (object) [
                'response' => false,
                'message'  => $e->getMessage()
            ];

        }
    }

}
