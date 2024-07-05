<?php

namespace App\Actions;

use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Storage;

class FileUploadAction
{    
    /**
     * fileRepository
     *
     * @var FileRepository
     */
    protected $fileRepository;
    
    /**
     * __construct
     *
     * @param  FileRepository $fileRepository
     * @return void
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Execute the file upload action.
     *
     * @param array $validatedData
     * @return \App\Models\File
     */
    public function execute(array $validatedData)
    {
        $file = $validatedData['file'];
        $path = $file->store('pet-shop', 'public');
        $fileData = [
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
        ];

        return $this->fileRepository->create($fileData);
    }
}
