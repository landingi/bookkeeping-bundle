<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;

final class WfirmaValueAddedTax
{
    private string $identifier;
    private ValueAddedTax $tax;
    public const NO_VAT = 'NP';

    public function __construct(string $identifier, ValueAddedTax $tax)
    {
        $this->identifier = $identifier;
        $this->tax = $tax;
    }

    public function getTax(): ValueAddedTax
    {
        return $this->tax;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
