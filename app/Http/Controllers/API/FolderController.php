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
use Auth;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use App\Models\FileAudio;
use App\Models\Permalink;


class FolderController extends Controller
{
    public $parent_id;

    public function __construct()
    {
        $this->middleware('auth')->except(['getChildFolders', 'getPublicFiles']);
    }

    public function shareFolder(Request $request) 
    {
        $folder = Folder::find($request->folderID);
        if ($folder) {
            $folder->update([
                'privacy'   => strtolower($request->privacy)
            ]);

            

            $folder->users()->sync( collect(collect($request->userValues)->pluck('id'), [Auth::user()->id]) );

            return Response()->json([
                "success"               => true,
                "folder"                => [
                    'id'            => $request->folderID,
                    'shared'        => Folder::find($request->folderID)->users,
                    'privacy'       => Folder::find($request->folderID)->privacy,
                    'permalink'     => Folder::getLink($request->folderID),
                    "owner"         => User::find($folder->user_id),
                    "created_at"    => date("F d, y", strtotime($folder->created_at)),
                ]
            ]);
        }
    }

    public function shareFile(Request $request) 
    {
        $file = File::find($request->fileID);

        if ($file) {

            $file->users()->sync(collect($request->userValues)->pluck('id'));

            $file->update([
                'privacy'   => strtolower($request->privacy)
            ]);

            return Response()->json([
                "success"           => true,
                "folder"            => [
                    'id'            => $request->folderID,
                    'shared'        => File::find($request->fileID)->users->pluck('id'),
                    'privacy'       => File::find($request->fileID)->privacy,
                    'permalink'     => File::getLink($request->fileID),
                    "owner"         => User::find($file->user_id),
                    "created_at"    => date("F d, y", strtotime($file->created_at)),
                ]
            ]);
        }
    }

    /* Public Child Folder Retrieval*/
    public function getChildFolders(Request $request) 
    {
        $folder_id = $request['public_folder_id'];

        //Currently Viewing person if logged in
        $viewer_id   = $request['public_viewer_id'];
        $folders = (Folder::getPublicFolder($folder_id, $viewer_id));

        return Response()->json([
            "success"               => true,
            "folders"               =>  $folders
        ]);   
    }
    
    /* Private Page Folders Retrieval*/
    public function folders(Request $request) 
    {
        //$request
        $folders = (Folder::getPrivateFolders());

        return Response()->json([
            "success"               => true,
            "folders"               =>  $folders
        ]);
    }



    /* Public Folder Files*/
    public function getPublicFiles(Request $request) 
    {
        $folderID   = $request['folder_id'];
        $folder     = Folder::find($folderID);
        
        $files      = $folder->files;

        //add shared
        foreach ($files as $key => $file) {
            $files[$key]['sharedTo'] = $file->shared; 
            $files[$key]['owner']    = User::find($file->user_id);
            $files[$key]['audioFiles'] = FileAudio::where('file_id', $file->id)->get();
        }
        
        return Response()->json([
            "success"               => true,
            'user_id'               => (isset(Auth::user()->id)) ? Auth::user()->id : null,
            'folder_id'             => $folder['id'],
            "folder_name"           => $folder['folder_name'],
            "folder_description"    => $folder['folder_description'],
            "permalink"             => Folder::getLink($folderID),
            "files"                 => json_decode($files),
            //thumbails
            "thumb_file_name"       => $folder['thumb_file_name'],
            "thumb_upload_name"     => $folder['thumb_upload_name'],
            "thumb_path"            => $folder['thumb_path'],

        ]);
    }
 
    /* Private Folder Files*/
    public function files(Request $request) 
    {
        $folderID   = $request['folder_id'];
        $folder     = Folder::find($folderID);
        $files      = $folder->files;
    
        //add shared
        foreach ($files as $key => $file) {
            $files[$key]['sharedTo'] = $file->shared; 
            $files[$key]['owner']    = User::find($file->user_id);
            //NEW
            $files[$key]['audioFiles']  = FileAudio::where('file_id', $file->id)->get();
        }

        return Response()->json([
            "success"               => true,
            'user_id'               => (isset(Auth::user()->id)) ? Auth::user()->id : null,
            'folder_id'             => $folder['id'],
            "folder_name"           => $folder['folder_name'],
            "folder_description"    => $folder['folder_description'],
            "permalink"             => Folder::getLink($folderID),
            "files"                 => json_decode($files),
            //thumbails
            "folder"                => $folder,
            "thumb_file_name"       => $folder->thumb_file_name,
            "thumb_upload_name"     => $folder->thumb_upload_name,
            "thumb_path"            => $folder->thumb_path,            
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
        abort_if(Gate::denies('filemanager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
                'user_id'               => Auth::user()->id,
                'parent_id'             => $request['parent_id'],
                'order_id'              => $next_order_id,
                'slug'                  => Str::slug($request['folder_name'], '-'),
                'folder_name'           => $request['folder_name'],
                'folder_description'    => $request['folder_description'],
            ];
        
            //Create Folder 
            $folderData = Folder::create($folderData);

            //generate permalink
            $permalink = Folder::getURLSegments($folderData->id);
            $permalink = Permalink::firstOrCreate([
                'id'            => $folderData->id,
                'permalink'     =>  $permalink
            ]);
          
            //send the repsone newly created data
            $folderData['permalink'] = Folder::getLink($folderData->id);

            $newFolder                  = Folder::find($folderData->id);
            $folderData['owner']        = User::find($newFolder->user_id);
            $folderData['created']   = date("F d, Y", strtotime($newFolder->created_at));

            return Response()->json([
                "success" => true,
                //"permalink" =>  $permalink,
                "folder_id" => $folderData->id,
                "folder"    => $folderData,
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
            'folder_description'    => $request['folder_description'],
            'owner'                 => User::find($folder->user_id),
            "created_at"            => date("F d, y", strtotime($folder->created_at)),
        ];

        //Update Folder 
        $folder->update($folderData);

        //update permalink
        $folderData['permalink'] = Folder::getLink($folder->id);



        //generate permalink when moving into parent
        $permalink = Folder::getPermalink($folder->id);
        Folder::updateChildrenPermalinks($folder->id);
        
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
        abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
                    if ($nodeTransferring->parent_id  !== $nodeTarget->id && $nodeData['node']->id !== $nodeData['target']->id) 
                    {
                        $nodeTransferring->parent_id    = $nodeTarget->id;
                        $nodeTransferring->order_id     =  1;
                        $nodeTransferring->save();

                        $folderData = [
                            'slug'                  => Str::slug($nodeTransferring->folder_name, '-'),
                            'folder_name'           => $nodeTransferring->folder_name,
                            'parent_id'             => $nodeTransferring->parent_id,
                            'folder_description'    => $nodeTransferring->folder_description,
                            "permalink"             => Folder::getLink($nodeData['node']->id)
                        ];

                        $permalinks = Folder::updateChildrenPermalinks($nodeTarget->parent_id);
                        return Response()->json([
                            "success"           => true,
                            "message"           => "Folder has been successfully transferred",
                            "folder"            =>  $folderData,
                            //"permalinks"        => $permalinks 
                        ]);


                    } else {

                        return Response()->json([
                            "success"           => false,
                            "message"           => "Error, Transferring to the same folder"
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
        abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
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


    public function uploadFolderThumbnail(Request $request)
    {

        $tempID = $request->input('tempID');

        if ($files = $request->file('uploadFiles')) {
                    
          

            //file path
            $originalPath = 'storage/uploads/folder_thumbnails/';

            $newFilename = time()."_". preg_replace('/\s+/', '_', $files->getClientOriginalName());

            $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);
            
            // Remove any runs of periods (thanks falstro!)
            $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

            //check if the filesize is not 0 / or cancelled
            if ($request->file('uploadFiles')->getSize() > 0) {

                // Save to folder table as thumbs
                try {

               
                    // for save original image name
                    //$path = $request->file('uploadFiles')->store('public/uploads');

                    //save in storage -> storage/public/uploads/
                    $path = $request->file('uploadFiles')->storeAs(
                        'public/uploads/folder_thumbnails/', $newFilename

                        ///'storage/uploads/folder_thumbnails/', $newFilename
                    );

                    //create public path -> public/storage/uploads/{folder_id}
                    $public_file_path = $originalPath . $newFilename;  

                    //Output JSON reply
                    return Response()->json([
                        "success"               => true,   
                        //'thumb_file_name'       => $request->file('uploadFiles')->getClientOriginalName(), //original filename
                        //'public_file_path'      => url($public_file_path),
                        'thumb_file_name'       => basename($public_file_path), //original filename
                        'thumb_upload_name'     => $request->file('uploadFiles')->getFileName(), //generated filename
                        'thumb_size'            => $request->file('uploadFiles')->getSize(),    
                        "path"                  => $public_file_path,
                      
                    ]); 

                } catch (\Exception $e) {
                 

                    return Response()->json([
                        "success"   => false,
                        "message"   => $e->getMessage()
                    ]);                    
                }




            } else {

                return Response()->json([
                    "success"   => false,
                    "message"   => "File Aborted or cancelled"
                ]);

            }

        } else {
            return Response()->json([
                "success" => false
            ]);

        }    


    }


    public function updateFolderThumbDetails(Request $request) 
    {
        $folder = Folder::find($request['folderID']); 

        if ($folder) {
        
            $updated = $folder->update([
               'thumb_file_name'    => $request['thumb_file_name'],
               'thumb_upload_name'  => $request['thumb_upload_name'],
               'thumb_path'         => $request['path']           
            ]);

            return Response()->json([
                "success" => true,
                "updated" => $updated,
            ]);

        } else {
        
            return Response()->json([
                "success"   => false,
                "message"   => "Folder not found"
            ]);        
        }
    }
}