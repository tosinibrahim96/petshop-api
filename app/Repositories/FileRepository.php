<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\File;

class FileRepository
{
    /**
     * Create a new file record.
     *
     * @param array $data
     * @return File
     */
    public function create(array $data): File
    {
        return File::create($data);
    }

    /**
     * Find a file by UUID.
     *
     * @param string $uuid
     * @return File|null
     */
    public function findByUuid(string $uuid): ?File
    {
        return File::where('uuid', $uuid)->first();
    }
}
