<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

interface Invoice
{
    public function print(Media $media): Media;
}
