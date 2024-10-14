<?php

namespace App\Http\Controllers\admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Page;
use Gate;



class PageController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //authenticated by has no "admin_access" in his role attached
            //@do: redirect to home (authenticated member will be his view)
            if (Gate::denies('admin_access')) {
                return redirect(route('home'));
            }
            return $next($request);           
        });        
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::orderBy('published_at')
                    //->where('is_published', true)
                    ->paginate(30);
        
        
        return view('admin.modules.pages.index', compact('pages'));    }

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
            'title'             => $request->title,
            'slug'              => $request->title,
            'content'           => $request->content,  
            'is_netenglish'     => ($request->is_netenglish == "NETENGLISH")? 1 : 0,
            'is_mytutor'        => ($request->is_mytutor == "MYTUTOR")? 1 : 0,
            'is_published'      => ($request->is_published == "PUBLISHED")? 1 : 0,
        ];

        $item = Page::create($data);     


        return redirect()->route('admin.pages.index')->with('message', 'Page created successfully!');
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
    public function edit(Page $page)
    {
        return view('admin.modules.pages.edit', compact('page'));
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
            'title'             => $request->title,
            //'slug'              => $request->title,
            'content'           => $request->content,  
            'is_netenglish'     => ($request->is_netenglish == "NETENGLISH")? 1 : 0,
            'is_mytutor'        => ($request->is_mytutor == "MYTUTOR")? 1 : 0,
            'is_published'      => ($request->is_published == "PUBLISHED")? 1 : 0,
        ];

        $page = Page::find($id);
        $item = $page->update($data);       


        return redirect()->route('admin.pages.index')->with('message', 'Page has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();  // This performs a soft delete by setting 'deleted_at'
        
        return redirect()->back()->with('message','Page has been deleted successfully');

    }
}
