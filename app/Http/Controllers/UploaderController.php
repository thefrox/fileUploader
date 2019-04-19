<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploaderController extends Controller
{
    function index()
    {
        return view('uploader/index');
    }
    
    function add()
    {
        return view('uploader/add');
    }
    
    function edit()
    {
        return view('uploader/edit');
    }
}
