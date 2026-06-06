<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService {
    public function compressAndSave(UploadedFile $file, $directory = 'uploads') {
        $filename = Str::random(20) . '.webp';
        $file->storeAs($directory, $filename, 'public');
        return $directory . '/' . $filename;
    }
}