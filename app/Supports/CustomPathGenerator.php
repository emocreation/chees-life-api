<?php

namespace App\Supports;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        $prefix = Str::lower(Str::after($media->getAttributes()['model_type'],'Models\\'));
        return $prefix . '/' . $this->getBasePath($media) . '/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        $prefix = Str::lower(Str::after($media->getAttributes()['model_type'],'Models\\'));
        return $prefix . '/' .$this->getBasePath($media) . '/c/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        $prefix = Str::lower(Str::after($media->getAttributes()['model_type'],'Models\\'));
        return $prefix . '/' .$this->getBasePath($media) . '/r/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if ($prefix !== '') {
            return $prefix . '/' . $media->getKey();
        }

        return $media->getKey();
    }
}
