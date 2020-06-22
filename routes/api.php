<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use App\Models\Folder;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    $data = $request->user();
    return Response::json($data);
});


Route::get('/test', function (Request $request) {

    $parentIDs = Folder::getParentIDs(32);

    //test 39 its the parent

    $result = array_intersect(array(39), $parentIDs);

    if ($result) {
        echo "array found, no way";

    } else {
       echo "no array go ahead"; 
    }

    echo "<pre>";
    print_r ($result);
});



Route::get('/test', function (Request $request) {

    $ids = Folder::getChildrenIDs(237);
    foreach ($ids as $id) {
        echo $id ." - " . Folder::getPermalink($id) ."<BR>";
    }
    
    print_r ($ids);
});*/


Route::middleware('auth:api')->get('/get_folders', 'API\FolderController@folders')->name('APIGetFolders');
Route::middleware('auth:api')->post('/get_folder_files', 'API\FolderController@files')->name('APIGetFolderFiles');
Route::middleware('auth:api')->post('/create_folder', 'API\FolderController@store')->name('APICreateFolder');
Route::middleware('auth:api')->post('/update_folder', 'API\FolderController@update')->name('APIUpdateFolder');
Route::middleware('auth:api')->post('/move_into_parent', 'API\FolderController@moveIntoParent')->name('APIMoveIntoParentFolder');
Route::middleware('auth:api')->post('/reorder_items', 'API\FolderController@reorderSiblingFolders')->name('APIReorderSiblingFolders');
Route::middleware('auth:api')->post('/delete_folder', 'API\FolderController@deleteFolder')->name('APIDeleteFolders');
/*
Route::middleware('auth:api')->post('/get_folder_files', function (Request $request) 
{
    $folderID   = $request['folder_id'];
    $folder     = Folder::find($folderID);
    $files      = $folder->files;

    return Response()->json([
        "success"               => true,
        "folder_name"           => $folder['folder_name'],
        "folder_description"    => $folder['folder_description'],
        "permalink"             => Folder::getLink($folderID),
        "files"                 => json_decode($files)
    ]);

});
*/

/*
Route::middleware('auth:api')->post('/create_folder', function (Request $request) 
{

    //disallow duplicate folder name
    $validator = Validator::make($request->all(), 
    [
        'folder_name' => [
            'required',
            'max:191',
            Rule::unique('folders')->where(function ($query) {
                return $query->where('parent_id', 0);
            })
        ],
        'folder_description' => [
            'max:191'
        ]
    ]);

    if ($validator->fails()) {
        return Response()->json([
            "success" => false,
            "message"   => $validator->errors()->all() 
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
        return Response()->json([
            "success" => true,
            "folder"    => $folderData
        ]);

    }
});
*/


/*
Route::middleware('auth:api')->post('/update_folder', function (Request $request) 
{
    abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $folder = Folder::where('id', $request['folder_id'])->first();

    //disallow duplicate folder name
    $validator = Validator::make($request->all(), 
    [
        'folder_name' => [
            'required',
            'max:191',
            Rule::unique('folders')->where(function ($query) {

                $folder = Folder::where('id', $request['folder_id'])->first();
            
                return $query->where('parent_id',  $folder->parent_id);


            })->ignore($request['folder_id'])
        ],
        'folder_description' => [
            'max:191'
        ]
    ]);

    if ($validator->fails()) {
        return Response()->json([
            "success" => false,
            "message"   => $validator->errors()->all() 
        ]);
    } else {
        $folderData = [
            'slug'                  => Str::slug($request['folder_name'], '-'),
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description']
        ];

        //Create Folder 
        $folderData = Folder::update($folderData);

        return Response()->json([
            "success" => true,
            "folder"    => $folderData
        ]);

    }
});
*/

/*
Route::middleware('auth:api')->post('/move_into_parent', function (Request $request) 
{
    $node   = (object) $request['node'];
    $parent = (object) $request['src'];
    $target = (object) $request['target'];

    $nodeData = [
        'parent'    => $parent,
        'node'      => $node,
        'target'    => $target
    ];

    $parentIDs = Folder::getParentIDs($nodeData['target']->id);

    $intersect = array_intersect(array($nodeData['node']->id), $parentIDs);

    if (!$intersect) {
        $nodeTransferring   = Folder::find($nodeData['node']->id);
        $nodeTarget         = Folder::find($nodeData['target']->id);
        if ($nodeTransferring && $nodeTarget) 
        {
    
            $childItems = Folder::where('parent_id', $nodeTarget->id)->orderBy('order_id', 'ASC')->get();
            $ctr = 2; //reserve first spot to the new addition
            foreach ($childItems as $childItem) {
                $childItem->order_id = $ctr;
                $childItem->save();
                $ctr = $ctr + 1;
            }
    
            //Insert to the first the new inserted node, 
            //if the parent id and nodeTarget is not the same only, since you will be dropping it to own folder
            if ($nodeTransferring->parent_id  != $nodeTarget->id) {
                $nodeTransferring->parent_id    = $nodeTarget->id;
                $nodeTransferring->order_id     =  1;
                $nodeTransferring->save();
            }
            return Response()->json([
                "success"           => true,
                "message"           => "transfer successfull"
            ]);
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

});
*/


/*
Route::middleware('auth:api')->post('/reorder_items', function (Request $request) 
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

        if ($insertInto['success'] == true) {

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
  

    
    //reorder trailing from transfered id
    $nodeTransferring   = Folder::find($node->id);
    $nodeTarget         = Folder::find($target->id);

    if ($nodeTransferring && $nodeTarget) {

        $orders = Folder::where('parent_id', $nodeTarget->parent_id)
        ->where('id', '!=', $nodeTransferring->id)
        ->where('order_id','>=', $nodeTarget->order_id)
        ->orderBy('order_id', 'ASC')->get();

        $ctr = $nodeTarget->order_id + 1000;
        foreach ($orders as $order) {
            $order->order_id = $ctr;
            $order->save();
            $ctr = $ctr + 1;
        }

        return Response()->json([
            "success"       => true,
            "parent"        => $parent->id,
            "parent_id"     => $nodeTarget->parent_id,
            "node"          => $nodeTransferring->id,
            "order_id"      => $nodeTarget->order_id,
            "target"        => $nodeTarget->id,
            "reorderMessage" => $reorder['message']
        ]);

    } else {
        return Response()->json([
            "success"   => false
        ]);

    }

   

});

*/