<?php
namespace App\Support;

class StoreImage
{
    /** Public URL for an image in public/images, versioned by file time so replaced images skip browser caches. */
    public static function url(?string $name): string
    {
        $name = ltrim((string) $name, '/');
        $path = public_path('images/'.$name);

        return '/images/'.$name.(is_file($path) ? '?v='.filemtime($path) : '');
    }
}
