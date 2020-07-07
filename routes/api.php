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
*/


Route::middleware('auth:api')->post('/get_folders', 'API\FolderController@folders')->name('APIGetFolders');
Route::middleware('auth:api')->post('/get_folder_files', 'API\FolderController@files')->name('APIGetFolderFiles');
Route::middleware('auth:api')->post('/create_folder', 'API\FolderController@store')->name('APICreateFolder');
Route::middleware('auth:api')->post('/update_folder', 'API\FolderController@update')->name('APIUpdateFolder');
Route::middleware('auth:api')->post('/share_folder', 'API\FolderController@share')->name('APIShareFolder');
Route::middleware('auth:api')->post('/move_into_parent', 'API\FolderController@moveIntoParent')->name('APIMoveIntoParentFolder');
Route::middleware('auth:api')->post('/reorder_items', 'API\FolderController@reorderSiblingFolders')->name('APIReorderSiblingFolders');
Route::middleware('auth:api')->post('/delete_folder', 'API\FolderController@deleteFolder')->name('APIDeleteFolders');

Route::post('/get_child_folders', 'API\FolderController@getChildFolders')->name('APIGetPublicFolders');
Route::post('/get_public_folder_files', 'API\FolderController@getPublicFiles')->name('APIGetPublicFolderFiles');