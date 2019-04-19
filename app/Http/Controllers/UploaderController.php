<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as Binary;
use Illuminate\Support\Facades\Validator;

class UploaderController extends Controller
{
    function index()
    {
        return view('uploader');
    }
       
    public function store(Request $file)
    {
        $file->validate([
        'name' => 'required:max:255',
        'email' => 'required:max:255',
        'width' => 'required|numeric',
        'height' => 'required|numeric',
        'weight' => 'required|numeric',
        'url' => 'required:max:255',       
        ]);

        File::create($file->all());


      return back()->with('message', 'Your file is submitted Successfully');
    }
    
    public function add(Request $request)
    {
        if ($request->isMethod('get'))
            return view('uploader');
        else {
            $validator = Validator::make($request->all(),
                [
                    'file' => 'image',
                ],
                [
                    'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
                ]);
            if ($validator->fails())
                return array(
                    'fail' => true,
                    'errors' => $validator->errors()
                );
            $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'uploads/';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('file')->move($dir, $filename);
            $file['width'] = 200;
            $file['height'] = 300;
            $file = new Request([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'width' => 320,
                'height' => '420',
                'weight' => '420',
                'url'   => $filename,
            ]);
            
            $this->store($file); //storage in db
            return $filename;
        }
    }

    public function remove($filename){
        File::delete('uploads/' . $filename);
        DB::table('users')->where('url', $filename)->delete();
    }
}
