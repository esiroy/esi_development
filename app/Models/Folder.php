<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permalink;
use App\Models\LessonHistory;
use App\Models\File;

use Gate;
use Auth;




class Folder extends Model
{

    public static $parent_ids = Array();

    public static $url_segment = Array();

    public static $folder_name = Array();

    public static $ids = Array();

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'slug',
        'folder_name', 
        'folder_description',
        'order_id',
        'privacy'
    ];

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


    public function getCreatedAtAttributes() {
        return date('F d, Y', strtotime($this->created_at));
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
        return $this->hasMany('App\Models\File')->orderBy('order_id', 'ASC');
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


    public static function getURLTitles($id, $separator = "/") 
    {
        $folder = Folder::where('id', $id)->first();

        if (isset($folder)) {
            if ($folder->parent_id !== null || $folder->parent_id !== 0) {
                //Sub Folder
                Folder::$folder_name[] = $folder->folder_name;

                Folder::getURLTitles($folder->parent_id);
            } else {
                //Root Folder
                Folder::$folder_name[] = $folder->folder_name;
            }
        }

        $segments = implode($separator , array_reverse(Folder::$folder_name));
        return $segments;
    }

    public static function getURLArray($id) 
    {
        Folder::$url_segment = array();
        $segments = Folder::getURLTitles($id);
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


    /**
        @todo; Next Folder Slide 

    **/
    public function getNextFolderID($memberID) {

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

            //new folder must be a new lesson
            $newFolderID       = $firstFolder->id;
        }



        return $newFolderID;    
    }


    public function getFirstFolder() {
    
        $folder     = Folder::where('privacy', 'public')->where('parent_id', 0)->orderBy('order_id', "ASC")->first(); 

        if ($folder) {
            return $folder;
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

}
