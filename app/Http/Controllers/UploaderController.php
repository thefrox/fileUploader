<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\File;
use App\Jobs\ResizePicture;
use Illuminate\Http\Request;
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

        //Store in db
        $id = File::create($file->all())->id;
        //start job delay queue to resize and notify customer with 5s delay
        $job = (new ResizePicture($id, 2))->delay(Carbon::now()->addSeconds(5));
        $this->dispatch($job);
        return $id;
    }
    
    public function add(Request $request)
    {
        if ($request->isMethod('get')){
            return view('uploader');
        } else {
            $validator = Validator::make($request->all(),
                [
                    'file' => 'image',
                ],
                [
                    'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
                ]);
            if ($validator->fails()){
                return array(
                    'fail' => true,
                    'errors' => $validator->errors()
                );
            }
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
                'weight' => filesize($dir.'/'.$filename)/1024, //Kb
                'url'   => $filename,
            ]);
            $id = $this->store($file);
            return array('url' => $filename, 'id' => $id);
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
        }
    }
}
