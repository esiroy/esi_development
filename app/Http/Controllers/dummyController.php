<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use App\Models\File;
use App\Models\User;

use Gate;
use Auth;

class dummyController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $folder     = Folder::find(3);

        if ($folder) {
            $files      = $folder->files;
            foreach ($files as $file) {
                print_r ($file->shared);
            }
            exit();
    
        }
 

        /*
        $permittedUsers = Folder::getPermittedUsers(66);

        print_r ($permittedUsers);
     
        exit();


        $user = User::find(1);
        
        if (Gate::forUser($user)->allows('permission', "filemanager_admin")) {
           echo "file manager admin allowed!";
        } else {
            echo "no allowed";
        }


        exit();
        $roles = User::find(1)->roles->pluck('title');

        echo "<pre>";

        foreach ($roles as $role) {
            echo $role ."<BR>";
        }


        $folders = User::find(1)->folders;

        $folders = Folder::whereIn('id', $folders->pluck('id'))->get();

        foreach ($folders as $folder) {
            echo $folder->folder_name ."<BR>";
        }
        
        
        $folder = Folder::find(1);

        echo "<pre>";
        foreach ($folder->users as $user) {
            echo $user->id ."<BR>";
        }
     
        
     

        echo "==== user has ";
        $user = User::find(2);

        $user->folders()->sync( array( 1, 2, 3, 4,5 ,5 ) );

        foreach ($user->folders as $folder) {
            echo "folder -> ". $folder->folder_name ."<BR>";
        }
        */
       

        //return view('dummy.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
