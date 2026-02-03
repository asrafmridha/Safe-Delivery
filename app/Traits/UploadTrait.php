<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait UploadTrait {

    public function uploadOne(UploadedFile $uploadedFile, $folder = null,$filename = null, $disk = 'public') {
        $name = !is_null($filename) ? $filename : Str::random(25);
        $file = $uploadedFile->storeAs($folder, $name, $disk);
        return $file;
    }


    public function uploadFile($image, $path) {
        $file_name      = time() . str_random() . rand(1, 10000) . '.' . $image->getClientOriginalExtension();
        $photo_path     = 'public/uploads/'.$path.'/' . $file_name;
        Image::make($image)->save($photo_path);
        return $file_name;
    }


    // public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null){
    //     $name = !is_null($filename) ? $filename : Str::random(25);
    //     $file = $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);
    //     return $file;
    // }

    // if ($request->has('profile_image')) {
    //     // Get image file
    //     $image = $request->file('profile_image');
    //     // Make a image name based on user name and current timestamp
    //     $name = Str::slug($request->input('name')).'_'.time();
    //     // Define folder path
    //     $folder = '/uploads/images/';
    //     // Make a file path where image will be stored [ folder path + file name + file extension]
    //     $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
    //     // Upload image
    //     $this->uploadOne($image, $folder, $name, 'public');
    //     // Set user profile image path in database to filePath
    //     $user->profile_image = $filePath;
    // }

}
