<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

final class WfirmaInvoiceItemTest extends TestCase
{
    private WfirmaMedia $media;

    protected function setUp(): void
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
</api>
XML;
        $this->media = new WfirmaMedia(new SimpleXMLElement($xml));
    }

    public function testItPrints(): void
    {
        $item = new WfirmaInvoiceItem(
            new Name('item name 1'),
            new Price(10),
            new ValueAddedTax(20),
            new NumberOfUnits(1)
        );

        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoicecontent>
        <name>item name 1</name>
        <unit>szt.</unit>
        <count>1</count>
        <price>10</price>
    </invoicecontent>
</api>
XML,
            $item->print($this->media)->toString()
        );
    }
}
