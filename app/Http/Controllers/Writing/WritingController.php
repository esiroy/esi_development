<?php

namespace App\Http\Controllers\Writing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WritingController extends Controller
{
    public function index() {

        return view("modules.writing.index");
    }

    public function ielts() {

        return vieW('modules.writing.ielts');

    }
}
