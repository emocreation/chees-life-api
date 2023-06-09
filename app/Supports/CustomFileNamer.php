<?php

namespace App\Supports;

use Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer;
use Str;

class CustomFileNamer extends DefaultFileNamer
{
    public function originalFileName(string $fileName): string
    {
        return Str::random();
    }
}
