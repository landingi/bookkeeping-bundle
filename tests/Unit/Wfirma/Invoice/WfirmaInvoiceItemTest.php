<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use PHPUnit\Framework\TestCase;

final class WfirmaInvoiceItemTest extends TestCase
{
    public function testItPrintsWithPLTax(): void
    {
        $item = new WfirmaInvoiceItem(
            new Name('item name 1'),
            new Price(10),
            new WfirmaValueAddedTax('0', new ValueAddedTax(23)),
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
        <price>0.1</price>
        <vat>23</vat>
    </invoicecontent>
</api>
XML,
            $item->print(WfirmaMedia::api())->toString()
        );
    }

    public function testItPrintsWithTaxId(): void
    {
        $item = new WfirmaInvoiceItem(
            new Name('item name 2'),
            new Price(20),
            new WfirmaValueAddedTax('222', new ValueAddedTax(20)),
            new NumberOfUnits(2)
        );

        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoicecontent>
        <name>item name 2</name>
        <unit>szt.</unit>
        <count>2</count>
        <price>0.2</price>
        <vat_code>
            <id>222</id>
        </vat_code>
    </invoicecontent>
</api>
XML,
            $item->print(WfirmaMedia::api())->toString()
        );
    }

    public function testItPrintsWithNoTax(): void
    {
        $item = new WfirmaInvoiceItem(
            new Name('item name 3'),
            new Price(30),
            new WfirmaValueAddedTax('NP', new ValueAddedTax(0)),
            new NumberOfUnits(3)
        );

        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoicecontent>
        <name>item name 3</name>
        <unit>szt.</unit>
        <count>3</count>
        <price>0.3</price>
        <vat>NP</vat>
    </invoicecontent>
</api>
XML,
            $item->print(WfirmaMedia::api())->toString()
        );
    }
}
