<?php

namespace Chernogolov\Fogcms\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class AvatarFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(80, 80);
    }
}