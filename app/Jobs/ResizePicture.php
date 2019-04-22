<?php

namespace App\Jobs;

use Log;
use App\File;
use Illuminate\Bus\Queueable;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ReviewPush\ImageResizer\ImageResizer;

class ResizePicture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $scale;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $scale)
    {
        Log::error('apres construc');
        $this->id = $id;
        $this->scale = $scale;
         error_log('Job failed');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get data from id
        $file = File::where('id', $this->id)->first();
        if (!empty($file->url))
        {
            $filepathurl = public_path('uploads/'.$file->url);
            $imageResizer = new ImageResizer($filepathurl, 100);
            // subdvising width proportionally
            $imageResizer->resizeSubdivise($this->scale);
            // replace picture and update data
            if($imageResizer->export($filepathurl))
            {
                list($file->width, $file->height) = getimagesize($filepathurl);
                $file->weight = round(filesize($filepathurl)/1024); //Kb
                File::where('id', $this->id)->update(array(
                    'width' => $file->width,
                    'height' => $file->height,
                    'weight' => $file->weight
                ));
                //Send notification by mail
                Mail::to($file->email)->send(new NotificationMail($file));
            }
        }
    }
}
