<?php

namespace App\Http\Services\ImageUpload;

class ImageUploadService
{
    public static function upload($fileRequest, $ImageModel)
    {
        $file = $fileRequest;

        $image_name = $file->getClientOriginalName();

        $path_image = "image/example/".$image_name;

        if (file_exists($path_image)) {
            $image_name = time() . $image_name;
        }

        $targetFile = $path_image . $image_name;
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        if ($fileType !== 'jpg' && $fileType !== 'png'){
            return redirect()->back()->with('error', 'Image format is invalid');
        }
        else {
            if($file->getSize() > 2000000){
                return redirect()->back()->with('error', 'Image size is more than 2 mg');
            }
        }
        $file->move("image/example/", $image_name);

        $result = $ImageModel::create([
            'image' => $image_name
        ]);

        if($result){
            return redirect()->back()->with('upload', 'Image was uploaded successful');
        }
    }

    public function updateImage($imageModel, $refImage , $refModel,$fileRequest)
    {
        $imageRef = $imageModel::where($refImage, $refModel->id)->where('id', $fileRequest)->first();

        $currentImage = $imageRef->image;
        $current_path = $_SERVER['DOCUMENT_ROOT'] . "/image/example/".$currentImage;


        $file = $fileRequest;

        $image_name = $file->getClientOriginalName();

        $path_image = "image/example/".$image_name;

        if (file_exists($path_image)) {
            $image_name = time() . $image_name;
        }

        $targetFile = $path_image . $image_name;
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        if ($fileType !== 'jpg' && $fileType !== 'png'){
            return redirect()->back()->with('error', 'Image format is invalid');
        }
        else {
            if($file->getSize() > 2000000){
                return redirect()->back()->with('error', 'Image size is more than 2 mg');
            }
        }


        unlink($current_path);

        $file->move("image/example/", $image_name);

        $result = $imageModel->update([
            $refImage => $refModel->id,
            'image' => $image_name
        ]);

        if($result){
            return redirect()->back()->with('upload', 'Image was updated successful');
        }
    }
}
