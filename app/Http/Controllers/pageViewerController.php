<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Page;

class pageViewerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $pages = Page::orderBy('published_at')->where('is_published', true)->paginate(1, '*', 'page');

        $lists = Page::orderBy('published_at')->where('is_published', true)->paginate(120, '*', 'list');       
        
        return view('modules.pages.index', compact( 'pages', 'lists'));
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->first();
        
        $lists = Page::orderBy('published_at')->where('is_published', true)->paginate(120, '*', 'list');       
        

        return view('modules.pages.view', compact( 'page', 'lists'));
    }

}
