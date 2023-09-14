<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permalink;
use App\Models\LessonHistory;
use App\Models\File;

use Gate;
use Auth;
use DB;



class Folder extends Model
{

    public static $folder = Array();

    public static $parent_ids = Array();

    public static $url_segment = Array();

    public static $folder_name = Array();

    public static $ids = Array();

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

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
        'updated_at' => 'datetime'
    ];


    public function getCreatedAtAttributes() {
        return date('F d, Y', strtotime($this->created_at));
    }


      
    /**
    * @return  subcategories of the folder.
    */
    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id');
    }

      
    /**
    * @return  subcategories of the folder.
    */
    public function subCats()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->where('privacy', 'public');
    }


    /**
     * @return  files of the folder.
     */
    public function files()
    {
        //return $this->hasMany(File::class, 'folder_id', 'id')->where('privacy', 'public');;

        //remove private since it is not needed
        return $this->hasMany(File::class, 'folder_id', 'id');
    }
    


    public function users() 
    {
        return $this->belongsToMany(User::class);
    }

    /** 
    * Determine if users is permitted to view this folder
    * @var userID   
    * @var folderID
    * @var returns  Boolen 
    */
    public static function hasPermission($userID, $folderID) 
    {
        //Look for the permitted users of this folder
        $usersSharedTo = [];
        $folder = Folder::find($folderID);

        //folder has multiple owners find this owners
        $folderUsers = $folder->users;
        foreach ($folderUsers as $folderUser) 
        {
            $usersSharedTo[] = $folderUser->id;
        }

        //return true it has permission, false for no permssion
        if (array_intersect([$userID], $usersSharedTo) || $userID == $folder->user_id) 
        {
            return true;

        } else {

            return false;
        }
    }

    public static function getPermittedUsers($folderID) 
    {
       //Look for the permitted users of this folder
       $shared = [];
       $folder = Folder::find($folderID);

       //folder has multiple owners find this owners
       $sharedFolderUserFolders = $folder->users;

       foreach ($sharedFolderUserFolders as $sharedFolderUserFolder) 
       {
           $shared[] = [
                        'id'    => $sharedFolderUserFolder->id,
                        'code'  => $sharedFolderUserFolder->id,
                        'name'  => $sharedFolderUserFolder->name,
                        ];
       }

       return $shared;
    }
 

    /**
     * @return Get the parent ids of the target node
     */
    public static function getParentIDs($id) {
        Folder::$parent_ids = Array();
        Folder::recursiveParentIDs($id);
        return Folder::$parent_ids;
    }

    public static function recursiveParentIDs($id) 
    {
        $item = Folder::where('id', $id)->first();
        if ($item) {
            if ($item->parent_id !== 0 || $item->parent_id !== null) {
                Folder::$parent_ids[] = $item->parent_id;
                Folder::recursiveParentIDs($item->parent_id);
            } else {
                Folder::$parent_ids[] = $item->parent_id;
            }
        }
    }

    

    /**   
     *  data['node']       : Items that is being transfered  
     *  data['target']     : Target before the item
    */
    public static function reorder($data) 
    {
        $nodeTransferring   = Folder::find($data['node']->id);
        $nodeTarget         = Folder::find($data['target']->id);
        if ($nodeTransferring && $nodeTarget) 
        {
            try {
                $folders  = Folder::where('parent_id', $nodeTarget->parent_id)->orderBy('order_id', 'ASC')->get();

                $ctr = 1;
                foreach ($folders as $folder) {
                    $folder->order_id = $ctr;
                    $folder->save();
                    $ctr = $ctr + 1;
                }
                $response = [
                    'success' => true,
                    'message'   => "Reordering of folders has been successfull",
                    'folders'=> Folder::where('parent_id', $nodeTarget->parent_id)->orderBy('order_id', 'ASC')->get()
                ];

            } catch (\Exception $e) {
                $response = [
                    'success' => false,
                    'message'   => $e->getMessage()
                ];
            }
        } else {
            $response = [
                'success'   => false,
                'message' => "selected folder and target folder to redorder was not found"
            ];
        }

        return $response;
    }

    /**   
     *  data['node']       : Items that is being transfered  
     *  data['target']     : Target before the item
    */
    public static function insertInto($data) 
    {
        $nodeTransferring   = Folder::find($data['node']->id);
        $nodeTarget         = Folder::find($data['target']->id);

        if ($nodeTransferring && $nodeTarget) 
        {

            $siblings = Folder::where('parent_id', $nodeTarget->parent_id)
                ->where('id', '!=', $nodeTransferring->id)
                ->where('folder_name', $nodeTransferring->folder_name)->get();

            if ($siblings->isEmpty()) 
            {
                try 
                {
                    //insert or transfer the node to a new location
                    $nodeTransferring->parent_id    = $nodeTarget->parent_id;
                    $nodeTransferring->order_id     = $nodeTarget->order_id;
                    $nodeTransferring->save();
                    $permalinks = Folder::updateChildrenPermalinks($nodeTarget->parent_id);
                    $response = [
                        'success'       => true,
                        'message'       => "Transfering to a parent folder  has been successfull",
                        'folder'        => $nodeTransferring,
                        'nodeTarget'    => $nodeTarget,
                    ];
                } catch (\Exception $e) {
                    $response = [
                        'success' => false,
                        'message'   => $e->getMessage()
                    ];
                }
            } 
            else 
            {
                $response = [
                    "success"           => false,
                    "message"           => "Error, duplicate folder found while inserting into parent folder"
                ];   
            }
         
        } 
        else 
        {
            $response = [
                'success'   => false,
                'message' => "selected folder transferring from and target folder was not found"
            ];         
        }
        return $response;
    }

    /**   
     *  data['node']       : Items that is being transfered  
     *  data['target']     : Target before the item
    */
    public static function reorderTrailingFolders($data) 
    {
        $nodeTransferring   = Folder::find($data['node']->id);
        $nodeTarget         = Folder::find($data['target']->id);

        if ($nodeTransferring && $nodeTarget) 
        {
            try 
            {
                $orders = Folder::where('parent_id', $nodeTarget->parent_id)
                ->where('id', '!=', $nodeTransferring->id)
                ->where('order_id','>=', $nodeTarget->order_id)
                ->orderBy('order_id', 'ASC')->get();

                $ctr = $nodeTarget->order_id + 1;
                foreach ($orders as $order) {
                    $order->order_id = $ctr;
                    $order->save();
                    $ctr = $ctr + 1;
                }

                $response = [
                    'success'       => true,
                    'message'       => "trailing folder reordering has been successfull",
                    'folder'        => $nodeTransferring,
                    'nodeTarget'    => $nodeTarget
                ];

            } 
            catch (\Exception $e) 
            {
                $response = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }

        } 
        else 
        {
            $response = [
                'success' => false,
                'message' => "selected folder id and target id was not found"
            ];
        }

        return $response;
    }

    public static function getURLSegments($id, $separator = "/") 
    {
        $folder = Folder::where('id', $id)->first();

        if (isset($folder)) {
            if ($folder->parent_id !== null || $folder->parent_id !== 0) {
                //Sub Folder
                Folder::$url_segment[] = $folder->slug;

                Folder::getURLSegments($folder->parent_id);
            } else {
                //Root Folder
                Folder::$url_segment[] = $folder->slug;
            }
        }

        $segments = implode($separator , array_reverse(Folder::$url_segment));
        return $segments;
    }



    public static function getURLTitleArray($id, $separator = "/") 
    {
        $folder = Folder::where('id', $id)->first();

        if (isset($folder)) {

            if ($folder->parent_id !== null || $folder->parent_id !== 0) {
                
                //Sub Folder
                Folder::$folder_name[] = $folder->folder_name;

                Folder::getURLTitleArray($folder->parent_id, false);
            } else {
                //Root Folder
                Folder::$folder_name[] = $folder->folder_name;
            }
        }

        $segments = implode($separator , array_reverse(Folder::$folder_name));
        return $segments;
    }
  

    public static function getURLTitles($id, $separator = "/", $reset = true, $folderNames = []) 
    {
        $folder = Folder::where('id', $id)->first();
    
        if (isset($folder)) {
            if ($folder->parent_id !== null || $folder->parent_id !== 0) {
                // Sub Folder
                $folderNames[] = $folder->folder_name;
                return Folder::getURLTitles($folder->parent_id, $separator, false, $folderNames);
            } else {
                // Root Folder
                $folderNames[] = $folder->folder_name;
            }
        }
    
        $segments = implode($separator, array_reverse($folderNames));
        
        if ($reset) {
            $folderNames = []; // Optionally reset folderNames
        }
        return $segments;
    }
    
    
    public static function getParentFolders($id) {
        Folder::$folder = [];
        return Folder::recurseParentFolders($id);
    }
    

    public static function recurseParentFolders($id) 
    {
        $folder = Folder::where('id', $id)->first();        
        if (isset($folder)) {        
            if ($folder->parent_id !== null || $folder->parent_id !== 0) {
                //Sub Folder
                $folder['formatted_folder_name'] = ucwords($folder->folder_name); 
                Folder::$folder[] = $folder;
                Folder::recurseParentFolders($folder->parent_id);
            } else {
                //Root Folder
                Folder::$folder[] = $folder;
            }
        }
        return array_reverse(Folder::$folder);
    }
   

    public static function getURLArray($id) 
    {
        Folder::$url_segment = array();
        $segments = Folder::getURLTitleArray($id);
        return array_reverse(Folder::$folder_name);
    }

    public static function getPermalink($id) 
    {
        Folder::$url_segment = array();
        $segments = Folder::getURLSegments($id);
        return  $segments;
    }

    public static function getLink($id) 
    {
        Folder::$url_segment = array();
        $segments = Folder::getURLSegments($id);
        return url('folder/' . $segments);
    }

    /**
     * Recursive parsing of folders of node tree
     * @returns JSON
     */
    public static function getPublicFolder($id, $viewer_id) 
    {
        return Folder::getTreeRootFolders($id, $viewer_id, false);
    }

    /**
     * Recursive parsing of folders of node tree
     * @returns JSON
     */
    public static function getPrivateFolders() 
    {
        $viewer_id = null;

        $draggable = (Gate::denies('filemanager_edit')) ? true : false; 
        
       return Folder::getTreeChildren(0, $viewer_id, $draggable);
    }
    
    /* 
    ** Parsing for all folders for tree display from folder with the $id to its children (this is for public / getPublicFolder function)
    ** @id          : The Folder id
    ** @viewer_id   : The current logged in viewer, returns null if not logged in
    ** @draggable   : Sets the mode of draggable of the tree node
    ** @folderData  : folder data
    */
    public static function getTreeRootFolders($id = 0, $viewer_id = null, $draggable = true, $folderData = []) 
    {
        if ( $viewer_id == null) 
        {
            //viewer is not logged in
            $rootFolders = Folder::where('id', $id)->where('privacy', 'public')->orderBy('order_id','ASC')->get();
        } 
        else 
        {
            //User is logged in (find the viewer id
            $user = User::find($viewer_id);

            //get folder id of what has been shared to me and this currend folder 
            $userFolderQuery = User::find($viewer_id)->folders;
            $userSharedFolders = Folder::where('id', $id)->whereIn('id', $userFolderQuery->pluck('id'))->get();
    
            if (Gate::forUser($user)->denies('permission', "filemanager_admin")) {
              
                //this will show only his folder, public folders, and shared
                $myFolders      = Folder::where('id', $id)->where('user_id', $viewer_id)->orderBy('order_id','ASC')->get();
                $publicFolders  = Folder::where('id', $id)->where('privacy', 'public')->orderBy('order_id','ASC')->get();

                $myFolders  = $myFolders->merge($publicFolders);
                $allFolders = $myFolders->merge($userSharedFolders);

                $rootFolders = $allFolders->values()->sortBy('order_id');

            } else {
                //(ADMIN MODE) this user has the rights to view all folders without restriction 
                $rootFolders = Folder::where('id', $id)->orderBy('order_id','ASC')->get();
            }
        }

        //search for children
        foreach($rootFolders as $rootFolder) {

            $user = User::find($rootFolder->user_id);

            //if denied the dragDisabled is true, or the default draggble
            //$draggable = (Gate::denies('filemanager_edit')) ? true : false; 
            
            $folderData[] = [
                'id'                    => $rootFolder->id,
                'pid'                   => $id,
                'name'                  => $rootFolder->folder_name,
                'description'           => $rootFolder->folder_description,
                //thumbails
                "thumb_file_name"       => $rootFolder->thumb_file_name,
                "thumb_upload_name"     => $rootFolder->thumb_upload_name,
                "thumb_path"            => $rootFolder->thumb_path,

                'permalink'             => Folder::getLink($rootFolder->id),
                'owner'                 => User::select('id', 'firstname', 'lastname', 'user_type', 'lastname')->find($rootFolder->user_id),
                'privacy'               => $rootFolder->privacy,
                'sharedTo'              => Folder::getPermittedUsers($rootFolder->id),
                'default-expanded'      => true,
                'created_at'            => date('F d, Y', strtotime($rootFolder->created_at)),
                'dragDisabled'          => $draggable,
                'addLeafNodeDisabled'   => true,
                'addTreeNodeDisabled'   => (Gate::denies('filemanager_create')) ? true : false,
                'editNodeDisabled'      => (Gate::denies('filemanager_edit')) ? true : false,
                'delNodeDisabled'       => (Gate::denies('filemanager_delete')) ? true : false,
                'children'              => Folder::getTreeChildren($rootFolder->id, $viewer_id, $draggable)
            ];
        }
        return $folderData;
    }

    /* 
    ** Parsing for all folders for tree display from ($parentID) to children (this is for public / getPublicFolder function)
    ** @id          : The Folder id
    ** @viewer_id   : The current logged in viewer, returns null if not logged in
    ** @draggable   : Sets the mode of draggable of the tree node
    ** @folderData  : folder data
    */
    public static function getTreeChildren($parentID, $viewer_id = null, $draggable = true, $folderData = []) 
    {
        if (Auth::check() || $viewer_id !== null) {

            // The user is logged in via Authentication
            $userID = (isset(Auth::user()->id))? Auth::user()->id : $viewer_id;
            $userFolderQuery = User::find($userID)->folders;

            // Shared Folders
            $userSharedFolders = Folder::where('parent_id', $parentID)->whereIn('id', $userFolderQuery->pluck('id'))->get();

            if (Gate::denies('filemanager_admin')) 
            {
                //this will show only his folder and any public folders
                $myFolders      = Folder::where('parent_id', $parentID)->where('user_id', $userID)->orderBy('order_id','ASC')->get();
                $publicFolders  = Folder::where('parent_id', $parentID)->where('privacy', 'public')->orderBy('order_id','ASC')->get();
                //merge public and your folders
                $allFolders     = $myFolders->merge($publicFolders);
                $myFolders      = $allFolders->values()->sortBy('order_id');
            } else {
                //this user has the rights to administer all folders
                $myFolders = Folder::where('parent_id', $parentID)->orderBy('order_id','ASC')->get();
            }

            //Users Shared Folders Merges with my folders
            $subFolders = $myFolders->merge($userSharedFolders);

        } else {

            //public folder view
            if ( $viewer_id == null) {
                //viewer is not logged in
                $subFolders = Folder::where('parent_id', $parentID)->where('privacy', 'public')->orderBy('order_id','ASC')->get();
            } 
            else 
            {
                //viewer is logged in
                $myFolders      = Folder::where('parent_id', $parentID)->where('privacy', 'public')->orderBy('order_id','ASC')->get();
                $publicFolders  = Folder::where('parent_id', $parentID)->where('user_id', $viewer_id)->orderBy('order_id','ASC')->get();
                $subFolders     = $myFolders->merge($publicFolders);
            }
        }
        
        foreach($subFolders as $subfolder) {

            $user = User::find($subfolder->user_id);

            //if denied the dragDisabled is true, or the default draggble
            $draggable = (Gate::denies('filemanager_edit')) ? true : false; 

            $folderData[] = [
                'id'                    => $subfolder->id,
                'pid'                   => $parentID,
                'name'                  => $subfolder->folder_name,
                'description'           => $subfolder->folder_description,
                //thumbails
                "thumb_file_name"       => $subfolder->thumb_file_name,
                "thumb_upload_name"     => $subfolder->thumb_upload_name,
                "thumb_path"            => $subfolder->thumb_path,

                'permalink'             => Folder::getLink($subfolder->id),
                'owner'                 => User::select('id', 'firstname', 'lastname', 'user_type', 'lastname')->find($subfolder->user_id),
                'sharedTo'              => Folder::getPermittedUsers($subfolder->id),
                'privacy'               => $subfolder->privacy,
                'created_at'            => date('F d, Y', strtotime($subfolder->created_at)),
                'default-expanded'      => true,
                'dragDisabled'          => $draggable,
                'addLeafNodeDisabled'   => true, //leaf is always disabled
                'addTreeNodeDisabled'   => (Gate::denies('filemanager_create')) ? true : false,
                'editNodeDisabled'      => (Gate::denies('filemanager_edit')) ? true : false,
                'delNodeDisabled'       => (Gate::denies('filemanager_delete')) ? true : false,
                'children'              => Folder::getTreeChildren($subfolder->id, $viewer_id, $draggable)
            ];
        }
        return $folderData;
    }


    /* 
    ** Returns plain array of all the childre of a node 
    */
    public static function getChildrenIDs($id) 
    {
        Folder::$ids = Array();
        Folder::recurseChildrenIDs($id);
        return Folder::$ids;
    }

    public static function recurseChildrenIDs($id) {
        $folders = Folder::where('parent_id', $id)->orderBy('order_id','ASC')->get();

        if($folders->count() > 0) {
            foreach($folders as $folder) {
                Folder::$ids[] = $folder->id;
                Folder::recurseChildrenIDs($folder->id);
            }
        }
    }

    public static function updateChildrenPermalinks($id) 
    {
        $updated = Array();
        $ids = Folder::getChildrenIDs($id);
        foreach ($ids as $id) {
            $permalink = "";
            $permalink = Folder::getPermalink($id);
            $updatePermalink = Permalink::find($id);
            if ($updatePermalink) {
                $updated[] = $permalink;
                $updatePermalink->update([
                    'permalink'     => $permalink
                ]);    
            }
        }
        return $updated;
    }



    /************************************************************
        SLIDES FOLDER SEARCH NEXT IDS
    ************************************************************/    
    public function getRecentLessonHistory($memberID, $status) 
    {    
        $lessonHistory = LessonHistory::where('member_id', $memberID)->where('status', $status)->orderBy("time_ended", 'DESC')->first();        
        if ($lessonHistory) { return $lessonHistory; } else { return false; }        
    }


    public function getRecentlyCompletedFolderID($memberID, $status = "COMPLETED") {
        $lessonHistory = LessonHistory::where('member_id', $memberID)->where('status', $status)->orderBy("time_ended", 'DESC')->first();
        if ($lessonHistory) { return $lessonHistory->folder_id; } else { return false; }        
    }


    public function getFirstFolder() {
    
        $parentFolder = Folder::where('privacy', 'public')
                        ->where('parent_id', 0)
                        ->orderBy('order_id', "ASC")->first();        

       $lastSubfolder = $this->getLastSubFolderRecursively($parentFolder);
      
       return $lastSubfolder;
    }    

    /**
        @todo; Next Folder Slide 
    */


    public function getNextFolderID_old($memberID) {

        $recentLessonHistory   = $this->getRecentLessonHistory($memberID, "COMPLETED");
        
        if ($recentLessonHistory) {   

            $recentHistoryID       = $recentLessonHistory->id;
            $recentHistoryFolderID = $recentLessonHistory->folder_id;
            $nextFolder             = $this->getNextFolder($recentLessonHistory->folder_id);

            if ($nextFolder) {
                $newFolderID       = $nextFolder->id;
            } else {

                $nextParentFolder = $this->getNextParentFolder($recentLessonHistory->folder_id);
                if ($nextParentFolder) {

                    $newFolderID       = $nextParentFolder->id;
                } else {           

                    $nextParentFolder = $this->getNextParentFolder($recentLessonHistory->folder_id, true);

                    if ($nextParentFolder) {

                        $newFolderID       = $nextParentFolder->id;

                    } else {     

                        $newFolderID       = null;                    
                    }            
                }        
            }

        } else {      

            $firstFolder = $this->getFirstFolder();

            if ($firstFolder) {
            
                //new folder must be a new lesson
                $newFolderID       = $firstFolder->id;

            } else {
            
                return null;
            }
        }


        return $newFolderID;    
    }
 



    public function getNextFolderID($memberID) {

        $recentLessonHistory   = $this->getRecentLessonHistory($memberID, "COMPLETED");

      

        if (isset($recentLessonHistory->folder_id)) {
            $currentFolder = $this->getCurrentFolder($recentLessonHistory->folder_id);
        } else {
            $currentFolder = $this->getFirstRootFolder(); 
        }

        //check if currentFolder is found, if the previous was deleted the folder so we can reference it
        if (!$currentFolder) {
            
            //we will go back to first folder
            $currentFolder = $this->getFirstRootFolder();
            
        }

        //check all the subfolders first for the current folder for the first lesson
        $nextSubFolderWithFiles = $this->findNextSubFolderWithFiles($currentFolder);    
        
        if ($nextSubFolderWithFiles) {            
            $folderID = $nextSubFolderWithFiles->id;
            return $folderID;
        }

        //proceed next sibling
        $nextFolderWithFiles = $this->findNextFolderWithFiles($currentFolder);

        if ($nextFolderWithFiles) {            
            $folderID = $nextFolderWithFiles->id;
            return $folderID;
        }

        //We need to flatten the array since we can't find it using conventional search
        $flattenedArray  = $this->flattenFolderStructureWithFiles();
        $next =  $this->findNextIDWithFiles($flattenedArray, $currentFolder->id);

        if (isset($next->id) && $next !== null) {
            $folderID = $next->id;
            return $folderID;
        } else {
            return null;
        }
    }

    public function getNextFolder($currentFolderID) {

        $currentFolder         = Folder::where('id', $currentFolderID)->where('privacy', 'public')->first(); 

        if ($currentFolder) 
        {
            $previousFolderParentID = $currentFolder->parent_id;
            $previousFolderOrderID  = $currentFolder->order_id;
            $nextFolderOrderID      = $previousFolderOrderID + 1;

            $nextLessonFolder = Folder::where('parent_id', $previousFolderParentID)->where('order_id', '>=', $nextFolderOrderID)
                        ->orderBy('order_id', 'ASC')
                        ->where('privacy', 'public')->first();

            if ($nextLessonFolder) {

                //echo " next folder" . $nextLessonFolder->id .  " parent id". $nextLessonFolder->parent_id ."<BR>";             

                if ($nextLessonFolder->parent_id == null || $nextLessonFolder->parent_id == 0)  {

                    $filesCounter = File::where('folder_id', $nextLessonFolder->id)->count();

                    if ($filesCounter == 0) {

                        $childFolder = Folder::where('parent_id', $nextLessonFolder->id)->orderBy('order_id', 'ASC')->where('privacy', 'public')->first();

                        if ($childFolder)                         
                            return $childFolder;                        
                        else
                            return $this->getNextFolder($nextLessonFolder->id);                                                
                    
                    } else {
                    
                        return $nextLessonFolder;
                    }

                } else 
                    return $nextLessonFolder;   

            } else

                return null;
            
        
        } else {
        
            return null;
        }


    }

    

     public function getNextParentFolder($folderID, $allowEmptyFiles = false) {
      
        //GET current folder and get the parent id.

        $currentFolder = Folder::where('id', $folderID)->first();

        if ($currentFolder)  {
        
            //select the parent folder and  determine next parent

            $parentFolder = Folder::where('id', $currentFolder->parent_id)->where('privacy', 'public')->first();

            if ($parentFolder) {

                $nextParentFolder = Folder::where('parent_id', $parentFolder->parent_id)->where('order_id', '>', $parentFolder->order_id )->where('privacy', 'public')->first();

                if ($nextParentFolder) {

                    $filesCounter = File::where('folder_id', $nextParentFolder->id)->count();
                
                    
                    if ($filesCounter == 0 && $allowEmptyFiles == false) {

                      
                        $nextSubFolder = Folder::where('parent_id', $nextParentFolder->id)->orderBy('order_id', 'ASC')->where('privacy', 'public')->first();

                        return $nextSubFolder;

                    } else {               
                    
              
                        return $nextParentFolder;
                    }

                  

                } else {               
            

                    return null;
                }
            }
        }     
    }   


    public function getSubFolders($id, $page, $itemsPerPage = 10) 
    {    
        if (!isset($id)) {
            $id = 0; //replace null to 0  
        } 

        $query = $this->where('parent_id', '=', $id)->whereHas('subCats')->with('subCats')->where('privacy', 'public')->orderBy('order_id', 'ASC');

        if ($itemsPerPage == "*") {
            return $query->get();
        } else {
            return $query->paginate($itemsPerPage, ['*'], 'page', $page);
        }
    }

    public function getFolderLessons($id, $page, $itemsPerPage = 10) 
    {

        $query = $this->where('parent_id', '=', $id)->doesntHave('subCats')->where('privacy', 'public')->orderBy('order_id', 'ASC');

        if ($itemsPerPage == "*") {
            return $query->get(['id', 'folder_name']);
        } else {
            return $query->paginate($itemsPerPage, ['*'], 'page', $page);
        }
   
        
    }


    public function getLastSubFolderRecursively($parentFolder) 
    {
       if (isset($parentFolder)) {            
           $subfolder     = Folder::where('privacy', 'public')->where('parent_id', $parentFolder->id)
                               ->orderBy('order_id', "ASC")
                               ->first(); 
           if ($subfolder) {
               return $this->getLastSubFolderRecursively($subfolder);
           } else {                
               return $parentFolder;
           }
       } else {            
           return null;
       }
    }    


    /* New Version */
    function getCurrentFolder($id) {
        $folder = Folder::where('id', $id)->first();
        if ($folder)
            return $folder;
        else 
            return null;
    }


    function getFirstRootFolder() {
        $firstFolder = Folder::where('parent_id', 0)
                        ->where('privacy', 'public')
                        ->orderBy('order_id', 'ASC')->first();
        if ($firstFolder) {
            return $firstFolder;
        } else {
            return null;
        }        
    }

    function findNextFolderWithFiles($currentFolder, $autoNextFolder = true) {

        // Search for the next folder with files at this level
        $query = Folder::where('parent_id', $currentFolder->parent_id)
                    ->where('privacy', 'public')
                    ->whereHas('files');

        if ($autoNextFolder == true) {
            $query->where('order_id', '>', $currentFolder->order_id);
        }
                   

        $nextFolderWithFiles = $query->first();

    
        if ($nextFolderWithFiles) {
            return $nextFolderWithFiles;
        }
    
        // If no matching folder is found at this level, recursively search in subfolders
        $subfolders = Folder::where('parent_id', $currentFolder->id)
                    ->orderBy('order_id', 'ASC')
                    ->where('privacy', 'public')
                    ->get();
        
        foreach ($subfolders as $subfolder) {
            $nextFolder = $this->findNextFolderWithFiles($subfolder, false);
            
            if ($nextFolder) {
                return $nextFolder;
            }
        }
    
        return null;
    }

    // Define a recursive function to find the next folder with files
    public function findNextSubFolderWithFiles($currentFolder) {
        // Search for the next folder with files in subfolders
        $subfolders = Folder::where('parent_id', $currentFolder->id)
            ->orderBy('order_id', 'ASC')
            ->where('privacy', 'public')
            ->whereHas('files')
            ->get();
        
        foreach ($subfolders as $subfolder) {
            $nextFolder = $this->findNextSubFolderWithFiles($subfolder);
            
            if ($nextFolder) {
                return $nextFolder;
            }
        }
    
        // If no matching folder is found in subfolders, search at this level
        $query = Folder::where('parent_id', $currentFolder->id)
            ->whereHas('files')
            ->where('privacy', 'public')
            ->orderBy('order_id', 'ASC');
    
        $nextFolderWithFiles = $query->first();
    
        if ($nextFolderWithFiles) {
            return $nextFolderWithFiles;
        }
    
        return null;
    }   

   


    public function findNextIDWithFiles($flattenedArray, $currentId) {
        $idIndexMap = [];
        
        // Build a mapping of IDs to their index positions in the flattened array
        foreach ($flattenedArray as $index => $folder) {
            $idIndexMap[$folder['id']] = $index;
        }
    
        // Find the index of the current ID
        $currentIndex = isset($idIndexMap[$currentId]) ? $idIndexMap[$currentId] : null;
    
        if ($currentIndex !== null) {
            // Iterate through the remaining elements in the array
            for ($i = $currentIndex + 1; $i < count($flattenedArray); $i++) {
                if (isset($flattenedArray[$i]['hasFiles']) && $flattenedArray[$i]['hasFiles']) {
                    return $flattenedArray[$i];
                }
            }
        }
    
        return null; // No next ID with 'hasFiles' set to true found
    }

    function findNextID($flattenedArray, $currentId) {
        $idIndexMap = [];
        
        // Build a mapping of IDs to their index positions in the flattened array
        foreach ($flattenedArray as $index => $folder) {
            $idIndexMap[$folder['id']] = $index;
        }
    
        // Find the index of the current ID
        $currentIndex = isset($idIndexMap[$currentId]) ? $idIndexMap[$currentId] : null;
    
        if ($currentIndex !== null && $currentIndex < count($flattenedArray) - 1) {
            // If the current ID is found and not the last in the array, return the next ID
            return $flattenedArray[$currentIndex + 1];
        }
    
        return null; // No next ID found
    }


    function flattenFolderStructureWithFiles($parentID = 0) {

        $result = [];

        $folders = Folder::where('parent_id', $parentID)
            ->orderBy('order_id', 'ASC')
            ->where('privacy', 'public')
            ->get();


        foreach ($folders as $folder) {

            if ($folder->files->count() > 0) {
                $folder->hasFiles = true;
                $result[] = $folder;
            } else {
                $folder->hasFiles = false;
                $result[] = $folder;                
            }

            // Recursively fetch subfolders and add them to the result
            $subfolders = $this->flattenFolderStructureWithFiles($folder->id);
            if (!empty($subfolders)) {
                $result = array_merge($result, $subfolders);
            }
        }
    
        return $result;
    }


    public function flattenFolderStructure($folderID) {

        $folders = Folder::where('parent_id', $folderID)
                
                    ->orderBy('order_id','ASC')->get();
        $result = [];
    
        foreach ($folders as $folder) {

            $result[] = [
                'id' => $folder->id,
                'folder_name' => $folder->folder_name,
            ];
    
            // Recursively fetch subfolders and add them to the result
            $subfolders = $this->flattenFolderStructure($folder->id);
            if (!empty($subfolders)) {
                $result = array_merge($result, $subfolders);
            }
        }
    
        return $result;
    }

}
