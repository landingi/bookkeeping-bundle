<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

interface Media
{
    public function with(string $key, string $value): self;
    public function toString(): string;
}
