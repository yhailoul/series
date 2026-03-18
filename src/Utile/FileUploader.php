<?php

namespace App\Utile;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function upload(UploadedFile $file,string $directory, string $name): string
    {

        $newFileName= ($name? $name.'-':'').uniqid().'.'.$file->guessExtension();
        $file->move($directory, $newFileName);
        return $newFileName;

    }

    public function delete(string $directory, string $name){
        return unlink($directory.DIRECTORY_SEPARATOR.$name);
    }
    public function update(string $directory, string $oldFileName,UploadedFile $file, string $newFileName =''): void
    {
        $this->delete($directory, $oldFileName);
        $this->upload($file,$directory, $newFileName);
    }
}
