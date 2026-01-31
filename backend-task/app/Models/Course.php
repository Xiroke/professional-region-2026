<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $guarded = [];

    protected $appends = ['img_url'];

    public function getImgUrlAttribute()
    {
        return url('storage/'.$this->img);
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function resizeImage($file)
    {
        $filename = 'mpic_'.Str::random().'.jpg';
        $width = 300;
        $height = 300;
        $path = storage_path('app/public/images/' . $filename);

        list($width_orig, $height_orig) = getimagesize($file->getRealPath());

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($file->getRealPath());
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        imagejpeg($image_p, $path, 100);
        imagedestroy($image_p);
        imagedestroy($image);

        return 'images/'.$filename;
    }
}
