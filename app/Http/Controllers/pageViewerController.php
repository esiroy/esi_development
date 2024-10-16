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
        if(env('APP_NETENGLISH') == true) {

            $pages = Page::orderBy('published_at')->where('is_netenglish', true)->where('is_published', true)->orderBy('created_at','DESC')->withoutTrashed()->paginate(1, '*', 'page');
            $lists = Page::orderBy('published_at')->where('is_netenglish', true)->where('is_published', true)->orderBy('created_at','DESC')->withoutTrashed()->paginate(120, '*', 'list');       

        } elseif(env('APP_NETENGLISH') == false) {

            $pages = Page::orderBy('published_at')->where('is_netenglish', false)->where('is_published', false)->orderBy('created_at','DESC')->withoutTrashed()->paginate(1, '*', 'page');
            $lists = Page::orderBy('published_at')->where('is_netenglish', false)->where('is_published', true)->orderBy('created_at','DESC')->withoutTrashed()->paginate(120, '*', 'list');          
        }
        
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

        if(env('APP_NETENGLISH') == true) {        

            $page   = Page::where('slug', $slug)->where('is_netenglish', true)->where('is_published', true)->first();
            $lists = Page::where('is_netenglish', true)->where('is_published', true)->orderBy('published_at')->paginate(120, '*', 'list');     
            
        } elseif(env('APP_NETENGLISH') == false) {  

            $page   = Page::where('slug', $slug)->where('is_netenglish', false)->where('is_published', true)->first();
            $lists  = Page::where('is_netenglish', false)->where('is_published', true)->orderBy('published_at')->paginate(120, '*', 'list');     

        }
        

        return view('modules.pages.view', compact( 'page', 'lists'));
    }

}
