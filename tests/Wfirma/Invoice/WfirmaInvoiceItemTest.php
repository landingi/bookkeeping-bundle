<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
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
            new WfirmaValueAddedTax('1111', new ValueAddedTax(20)),
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
        <vat_code>
            <id>1111</id>
        </vat_code>
    </invoicecontent>
</api>
XML,
            $item->print($this->media)->toString()
        );
    }
}
