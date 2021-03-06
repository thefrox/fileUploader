<?php

namespace BlueMega\ImageResizer;

class ImageResizer {

    protected $file; // image resource
    protected $exif;
    protected $width, $height, $type;
    protected $quality;

    /**
     * Constructor - file path can be passed here
     * 
     * @param string
     * @return Object
     */
    public function __construct($filename = NULL, $quality = 75)
    {
        if ($quality < 0 || $quality > 100)
        {
            throw new Exception\InvalidImageQualityException('The image quality is invalid');
        }

        $this->width = $this->height = 0;
        $this->quality = $quality;

        if ($filename != NULL)
        {
            $this->load($filename);
        }
    }

    /**
     * Load an image file, type can be jpg, png or gif
     * 
     * @param string
     * @return Object
     */
    public function load($filename = NULL)
    {
        if ( ! extension_loaded('gd'))
        {
            
        }

        if(function_exists('exif_read_data'))
        {  
            $this->exif = @exif_read_data($this->filename);
            list($this->width, $this->height, $this->type) = getimagesize($filename);

            switch ($this->type)
            {
                case IMAGETYPE_JPEG:
                    $this->file = imagecreatefromjpeg($filename);
                    break;
                case IMAGETYPE_PNG:
                    $this->file = imagecreatefrompng($filename);
                    break;
                case IMAGETYPE_GIF:
                    $this->file = imagecreatefromgif($filename);
                    break;
                default:
                    throw new Exception\InvalidImageInputTypeException('Attempted to load a non-supported image');
            }
            return $this;
        }else{
            throw new \Exception('The PHP Exif extension does not exist!');
        }
    }


    /**
     * Resize the current image resource by subdivising current size
     * 
     * @param integer
     * @return Object
     */
    public function resizeSubdivise($scale = 2)
    {
        $newWidth = round($this->width / $scale);
        $newHeight = round($this->height / $scale);
        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($canvas, $this->file,
                            0, 0,
                            0, 0,
                            $newWidth, $newHeight,
                            $this->width, $this->height);

        $this->file = $canvas;
        $this->setImageSize();

        return $this;
    }


    /**
     * Save the image file to disk
     * 
     * @param string
     * @param boolean
     * @param string
     * @return Object
     */
    public function export($filename = FALSE, $type = 'jpg')
    {
        $this->setImageSize();
        switch ($type)
        {
            case 'jpg':
                if ( ! imagejpeg($this->file, $filename, $this->quality))
                    throw new Exception\FileNotWritableException('jpg file could not be saved!');
                break;
            case 'png':
                imagealphablending($this->file, FALSE);
                imagesavealpha($this->file, TRUE);
                if ( ! imagepng($this->file, $filename))
                    throw new Exception\FileNotWritableException('png file could not be saved!');
                break;
            case 'gif':
                if ( ! imagegif($this->file, $filename, $this->quality))
                    throw new Exception\FileNotWritableException('gif file could not be saved!');
                break;
            default:
                throw new Exception\InvalidImageOutputTypeException('Bad filetype given, must be jpg, png or gif');
        }
        return $this;
    }

    /**
     * Set image width and height attributes for the current image resource
     * 
     * @return null
     */
    protected function setImageSize()
    {
        $this->width = imagesx($this->file);
        $this->height = imagesy($this->file);
    }
}
