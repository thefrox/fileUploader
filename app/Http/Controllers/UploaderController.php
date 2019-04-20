<?php

namespace App\Http\Controllers;

use App\File;
use App\Mail\NotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File as Binary;
use Illuminate\Support\Facades\Validator;

class UploaderController extends Controller
{
    function index()
    {
        $pictures = File::orderBy('id', 'DESC')->get(array('url','id')); //show by desc id
        return view('uploader')->with(['pictures'=>$pictures]);
    }
       
    public function store(Request $file)
    {
        $file->validate([
            'name'    => 'required:max:255',
            'email'   => 'required:max:255',
            'width'   => 'required|numeric',
            'height'  => 'required|numeric',
            'weight'  => 'required|numeric',
            'url'     => 'required:max:255',       
        ]);

        File::create($file->all());

        //Send notification by mail
        Mail::to("receiver@example.com")->send(new NotificationMail($file));

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
            list($width, $height) = getimagesize($dir.'/'.$filename);
            $file = new Request([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'width' => $width,
                'height' => $height,
                'weight' => filesize($dir.'/'.$filename)/pow(1024, 2), //Mo - $request->file('file')->getSize(),
                'url'   => $filename,
            ]);
            
            $this->store($file); //storage in db
            return $filename;
        }
    }

    public function remove(Request $request){
        //remove from db
        $file = File::where('id', $request->get('id'))->first();
        if (!empty($file->url)){
            $file->delete();
        }
        
        //remove from binary
        if(Binary::exists(public_path('uploads/'.$request->get('url')))){
            Binary::delete(public_path('uploads/'.$request->get('url')));
        }else{
            return "404";
        }
    }
}
