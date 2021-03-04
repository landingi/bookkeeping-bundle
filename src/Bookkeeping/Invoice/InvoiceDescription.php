<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use function implode;

final class InvoiceDescription
{
    /**
     * @var string[]
     */
    private array $description;

    public function __construct(string ...$description)
    {
        $this->description = $description;
    }

    public function toString(): string
    {
        return implode(' ', $this->description);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
