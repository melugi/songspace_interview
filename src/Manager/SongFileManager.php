<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class SongFileManager
{
    protected string $targetDirectory;
    protected SluggerInterface $slugger;

    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file): string
    {
        $uploadedFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedFilename = $this->slugger->slug($uploadedFilename);
        $songFileName = $sanitizedFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->targetDirectory, $songFileName);
        } catch (FileException $e) {
            # Just rethrowing for now
            throw $e;
        }

        return $songFileName;
    }

    public function delete(string $fileName): bool
    {
        try {
            return unlink($this->targetDirectory . $fileName);
        } catch (FileException $e) {
            # Just rethrowing for now
            throw $e;
        }
    }
}


