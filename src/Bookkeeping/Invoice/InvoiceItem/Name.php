<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;

final class Name
{
    private string $name;

    /**
     * @throws InvoiceItemException
     */
    public function __construct(string $name)
    {
        if (true === empty(trim($name))) {
            throw new InvoiceItemException('Name cannot be an empty value!');
        }

        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
