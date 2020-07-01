<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permalink;
use Gate;

class Folder extends Model
{

    public static $parent_ids = Array();

    public static $url_segment = Array();

    public static $ids = Array();



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'slug',
        'folder_name', 
        'folder_description',
        'order_id'
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


    /**
     * @return Get the files of the folder.
     */
    public function files()
    {
        return $this->hasMany('App\Models\File');
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

    public static function getURLSegments($id) 
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

        $segments = implode("/", array_reverse(Folder::$url_segment));
        return $segments;
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
    public static function getPublicFolder($id) {
        return Folder::getTreeRootFolders($id, false);
    }

    /**
     * Recursive parsing of folders of node tree
     * @returns JSON
     */
    public static function getFoldersRecursively() 
    {
       return Folder::getTreeChildren(0, (Gate::denies('filemanager_edit')) ? false : true);
    }

    /* 
    ** Parsing for all folders for tree display from parent to children
    */
    public static function getTreeRootFolders($parentID = 0, $draggable = true, $folderData = []) 
    {
        $rootFolders = Folder::where('id', $parentID)->orderBy('order_id','ASC')->get();

        //search for children
        foreach($rootFolders as $rootFolder) {
            $folderData[] = [
                'id'                    => $rootFolder->id,
                'name'                  => $rootFolder->folder_name,
                'description'           => $rootFolder->folder_description,
                'permalink'             => Folder::getLink($rootFolder->id),
                'default-expanded'      => true,
                'pid'                   => $parentID,
                'dragDisabled'          => !$draggable,
                'addLeafNodeDisabled'   => true,
                'addTreeNodeDisabled'   => (Gate::denies('filemanager_create')) ? true : false,
                'editNodeDisabled'      => (Gate::denies('filemanager_edit')) ? true : false,
                'delNodeDisabled'       => (Gate::denies('filemanager_delete')) ? true : false,
                'children'=> Folder::getTreeChildren($rootFolder->id, $draggable)
            ];
        }
        return $folderData;
    }

    /* 
    ** Parsing for all folders for tree display 
    */
    public static function getTreeChildren($parentID, $draggable = true, $folderData = []) 
    {
        $subFolders = Folder::where('parent_id', $parentID)->orderBy('order_id','ASC')->get();

        foreach($subFolders as $subfolder) {
            $folderData[] = [
                'id'                    => $subfolder->id,
                'name'                  => $subfolder->folder_name,
                'description'           => $subfolder->folder_description,
                'permalink'             => Folder::getLink($subfolder->id),
                'pid'                   => $parentID,
                'default-expanded'      => true,
                'dragDisabled'          => !$draggable,
                'addLeafNodeDisabled'   => true, //leaf is always disabled
                'addTreeNodeDisabled'   => (Gate::denies('filemanager_create')) ? true : false,
                'editNodeDisabled'      => (Gate::denies('filemanager_edit')) ? true : false,
                'delNodeDisabled'       => (Gate::denies('filemanager_delete')) ? true : false,
                'children'      => Folder::getTreeChildren($subfolder->id, $draggable)
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

}
