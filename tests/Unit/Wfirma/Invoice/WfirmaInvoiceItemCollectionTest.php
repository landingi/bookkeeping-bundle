<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Invoice;

use Exception;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItemCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

final class WfirmaInvoiceItemCollectionTest extends TestCase
{
    public function testItIsProperCollection(): void
    {
        $this->expectException(Exception::class);
        //@phpstan-ignore-next-line
        new WfirmaInvoiceItemCollection([
            new WfirmaInvoiceItem(
                new Name('item name 1'),
                new Price(10),
                new WfirmaValueAddedTax('1111', new ValueAddedTax(20)),
                new NumberOfUnits(1)
            ),
            new stdClass(),
        ]);
    }
}
