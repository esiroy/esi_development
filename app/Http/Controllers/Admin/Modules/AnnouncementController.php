<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Tutor;
use App\Models\Member;
use App\Models\Lesson;
use App\Models\Announcement;
use App\Models\AnnouncementUserType;

use Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements = Announcement::orderBy('id', 'desc')
                        ->where('valid', 1)
                        ->paginate(30);
        
        
        return view('admin.modules.announcement.index', compact('announcements'));
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

        $data = [            
            'date_from'         => date('Y-m-d H:i:s', strtotime($request->dateFrom)),
            'date_to'           => date('Y-m-d H:i:s', strtotime($request->dateTo)),
            'body'              => $request->body,
            'updatedby_user_id' => Auth::user()->id,
            'valid'             => true,
            'is_hidden'         => ($request->isHidden == "on")? 1 : 0
        ];
        $item = Announcement::create($data);

        //announcement user type
        if (is_array($request->usertypes)) {
            foreach ($request->usertypes as $type) 
            {
                $type = [            
                    'announcement_id'    => $item->id,
                    'element'            => $type
                ];
                AnnouncementUserType::create($type);
            }
    
        }


        return redirect()->route('admin.announcement.index')->with('message', 'Announcement added successfully!');
         
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
    public function edit(Announcement $announcement)
    {
        return view('admin.modules.announcement.edit', compact('announcement'));
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

        $data = [            
            'date_from'         => date('Y-m-d H:i:s', strtotime($request->dateFrom)),
            'date_to'           => date('Y-m-d H:i:s', strtotime($request->dateTo)),
            'body'              => $request->body,
            'updatedby_user_id' => Auth::user()->id,
            'valid'             => true,
            'is_hidden'         => ($request->isHidden == "on")? 1 : 0
        ];

        $announcement = Announcement::find($id);
        $item = $announcement->update($data);

        //announcement user type
        AnnouncementUserType::where('announcement_id', $id)->delete();

        if (is_array($request->usertypes)) {
            foreach ($request->usertypes as $type) 
            {
                $type = [            
                    'announcement_id'    => $id,
                    'element'            => $type
                ];                
                AnnouncementUserType::create($type);
            }
    
        }

        return redirect()->route('admin.announcement.index')->with('message', 'Announcement updated successfully!');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->back()->with('message','Announcement has been deleted successfully');
    }
}
