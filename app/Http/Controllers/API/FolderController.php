<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use Gate;
use Validator;
use Input;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use App\Models\Permalink;

class FolderController extends Controller
{
    public $parent_id;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function folders(Request $request) 
    {
        $folders = (Folder::getFoldersRecursively());

        return Response()->json([
            "success"               => true,
            "folders"               =>  $folders
        ]);
    }


    public function files(Request $request) 
    {
        $folderID   = $request['folder_id'];
        $folder     = Folder::find($folderID);
        $files      = $folder->files;
    
        return Response()->json([
            "success"               => true,
            'folder_id'             => $folder['id'],
            "folder_name"           => $folder['folder_name'],
            "folder_description"    => $folder['folder_description'],
            "permalink"             => Folder::getLink($folderID),
            "files"                 => json_decode($files)
        ]);
    }

    /**
     * Store a newly created folder in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        $this->parent_id = $request['parent_id'];

        //disallow duplicate folder name
        $validator = Validator::make($request->all(), 
        [
            'folder_name' => [
                'required',
                'max:191',
                Rule::unique('folders')->where(function ($query) {
                    return $query->where('parent_id', $this->parent_id);
                })
            ],
            'folder_description' => [
                'max:191'
            ]
        ]);

        if ($validator->fails()) {
            return Response()->json([
                "success" => false,
                "message"   => implode(", ", $validator->errors()->all()) 
            ]);
        } else {

            $folder = Folder::where('parent_id', $request['parent_id'])->get();

            $next_order_id =  ($folder->max('order_id')) ? $folder->max('order_id') + 1 : 1;

            $folderData = [
                'slug'                  => Str::slug($request['folder_name'], '-'),
                'folder_name'           => $request['folder_name'],
                'parent_id'             => $request['parent_id'],
                'folder_description'    => $request['folder_description'],
                'order_id'              => $next_order_id
            ];
        
            //Create Folder 
            $folderData = Folder::create($folderData);

            //generate permalink
            $permalink = Folder::getURLSegments($folderData->id);
            $permalink = Permalink::firstOrCreate([
                'id'            => $folderData->id,
                'permalink'     =>  $permalink
            ]);

            return Response()->json([
                "success" => true,
                "permalink" =>  $permalink,
                "folder_id" => $folderData->id,
                "folder"    => $folderData
            ]);



        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folder = Folder::where('id', $request['folder_id'])->first();

        $this->parent_id = $folder->parent_id;

        $validator = Validator::make($request->all(), 
        [
            'folder_name' => [
                'required',
                'max:191',
                Rule::unique('folders')->where(function ($query) {
                    return $query->where('parent_id', $this->parent_id);
                })->ignore($folder->id)
            ],
            'folder_description' => [
                'max:191'
            ]
        ]);

        if ($validator->fails()) {
            return Response()->json([
                "success" => false,
                "message"   => implode($validator->errors()->all())
            ]);
        }

        $folderData = [
            'id'                    => $folder->id,
            'parent_id'             => $folder->parent_id,
            'slug'                  => Str::slug($request['folder_name'], '-'),
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description']
        ];

        //Update Folder 
        $folder->update($folderData);

        //generate permalink when moving into parent
        $permalink = Folder::getURLSegments($folder->id);
        $queryPermalink = Permalink::find($folder->id);
        if (isset($queryPermalink)) {
            $queryPermalink->update([
                'permalink'     => $permalink
            ]);
        } else {
            Permalink::create([
                'id'            => $folder->id,
                'permalink'     => $permalink
            ]);  
        }


        return Response()->json([
            "success" => true,
            "folder"    => $folderData
        ]);
    }

    /**
     * Update the folder to new parent specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveIntoParent(Request $request) 
    {

        $node   = (object) $request['node'];
        $parent = (object) $request['src'];
        $target = (object) $request['target'];
    
        $nodeData = [
            'parent'    => $parent,
            'node'      => $node,
            'target'    => $target
        ];
    
        //disallow dragging itself to child folder(s) making the folder an orphan
        $parentIDs = Folder::getParentIDs($nodeData['target']->id);
        $intersect = array_intersect(array($nodeData['node']->id), $parentIDs);
    
        if (!$intersect) 
        {
            $nodeTransferring   = Folder::find($nodeData['node']->id);
            $nodeTarget         = Folder::find($nodeData['target']->id);

            if ($nodeTransferring && $nodeTarget) 
            {

                $siblings = Folder::where('parent_id', $nodeTarget->id)->where('folder_name', $nodeTransferring->folder_name)->get();

                if ($siblings->isEmpty()) {

                    //reorder child items
                    $reorderChildItems = Folder::where('parent_id', $nodeTarget->id)->orderBy('order_id', 'ASC')->get();

                    $ctr = 2; //reserve first spot to the new addition
                    foreach ($reorderChildItems as $childItem) {
                        $childItem->order_id = $ctr;
                        $childItem->save();
                        $ctr = $ctr + 1;
                    }

                    //Insert to the first the new inserted node, 
                    //if the parent id and nodeTarget is not the same only, since you will be dropping it to own folder
                    if ($nodeTransferring->parent_id  != $nodeTarget->id) 
                    {
                        $nodeTransferring->parent_id    = $nodeTarget->id;
                        $nodeTransferring->order_id     =  1;
                        $nodeTransferring->save();

                        $permalinks = Folder::updateChildrenPermalinks($nodeTarget->parent_id);


                        return Response()->json([
                            "success"           => true,
                            "message"           => "Folder has been successfully transferred",
                            "permalinks"         => $permalinks 
                        ]);    

                        /*
                        //generate permalink when moving into parent
                        $permalink = Folder::getURLSegments($nodeTransferring->id);
                        $queryPermalink = Permalink::find($nodeTransferring->id);
                        if ($queryPermalink) 
                        {
                            try {
                                $queryPermalink->update([
                                    'permalink'     => $permalink
                                ]);

                                return Response()->json([
                                    "success"           => true,
                                    "message"           => "transfer successfull"
                                ]);

                            } catch (\Exception $e) {

                                return Response()->json([
                                    'success' => false,
                                    'message'   => $e->getMessage()
                                ]);

                            }

                        } else {

                            try {
                                Permalink::create([
                                    'id'            => $nodeTransferring->id,
                                    'permalink'     => $permalink
                                ]);

                                return Response()->json([
                                    "success"           => true,
                                    "message"           => "transfer successfull"
                                ]);

                             } catch (\Exception $e) {

                                return Response()->json([
                                    'success' => false,
                                    'message'   => $e->getMessage()
                                ]);
                            }

                        }
                        */


                    } else {

                        return Response()->json([
                            "success"           => false,
                            "message"           => "Error, Transferring to the same folder found"
                        ]);      
                    }


                    
                } else {
                    return Response()->json([
                        "success"           => false,
                        "message"           => "Error, duplicate folder found"
                    ]);                  
                }
        
           
            } else {
                return Response()->json([
                    "success"           => true,
                    "message"           => "Error found, folder id was not found"
                ]);
            }
        } else {
            //transfer failed since it is adding himself it into his own child
            return Response()->json([
                "success"           => false,
                "message"           => "You can't add the parent folder into its own child"
            ]);
        }

    }


    /**
     * Update the folder order withing its sibling specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorderSiblingFolders(Request $request) 
    {
        $node   = (object) $request['node'];
        $parent = (object) $request['src'];
        $target = (object) $request['target'];
    
        $nodeData = [
            'parent'    => $parent,
            'node'      => $node,
            'target'    => $target
        ];
    
        $reorder = Folder::reorder($nodeData);
    
        if ($reorder['success'] == true) 
        {
            $insertInto = Folder::insertInto($nodeData);
    
            if ($insertInto['success'] === true) {
    
                $reorderTrailingFolder = Folder::reorderTrailingFolders($nodeData);
    
                if ($reorderTrailingFolder['success'] == true) 
                {
                    return Response()->json([
                        "success"           => true,
                        "message"           => [
                                                $reorder['message'],
                                                $insertInto['message'],
                                                $reorderTrailingFolder['message']
                                            ],
                        "parent"            => $parent->id,
                        "parent_id"         => $insertInto['nodeTarget']->parent_id,
                        "node"              => $insertInto['nodeTarget']->id,
                        "order_id"          => $insertInto['nodeTarget']->order_id,
                        "target"            => $insertInto['nodeTarget']->id
                    ]);
                } else {
    
                    //reOrder Trailing failed
                    return Response()->json([
                        "success"           => false,
                        "message"           => $reorderTrailingFolder['message']
                    ]);
                }
    
            } else {
    
                //Insert Into Failed
                return Response()->json([
                    "success"           => false,
                    "message"           => $insertInto['message']
                ]);
            }
        } else {
            //reorder failed
            return Response()->json([
                "success"           => false,
                "message"           => $insertInto['message']
            ]);
        }
    }


    public function deleteFolder(Request $request)
    {
        abort_if(Gate::denies('filemanager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentFolder = Folder::find($request->id);

        if ($parentFolder) {
            Storage::deleteDirectory("public/uploads/". $parentFolder->id);
            $parentFolder->delete();
        }

        $ids = Folder::getChildrenIDs($request->id);
        foreach ($ids as $id) 
        {
            $folder = Folder::find($id);
            if ($folder) {
                Storage::deleteDirectory("public/uploads/". $folder->id);
                $folder->delete();
            }
        }
    }

}