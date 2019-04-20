<?php

namespace Chernogolov\Fogcms\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class HouseFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(220, 200);
    }
}