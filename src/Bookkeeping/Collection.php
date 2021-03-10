<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use ArrayIterator;
use IteratorAggregate;
use function array_merge;

class Collection implements IteratorAggregate
{
    protected array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function getAll(): array
    {
        return $this->items;
    }

    public function merge(self $collection): self
    {
        return new self(array_merge($this->items, $collection->items));
    }
}
