<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItemCollection;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

final class WfirmaInvoiceTest extends TestCase
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
        $invoice = new WFirmaInvoice(
            new InvoiceIdentifier('2'),
            new InvoiceSeries(),
            new InvoiceDescription('Description Example'),
            new InvoiceItemCollection([]),
            new Person(
                new ContractorIdentifier('100'),
                new ContractorName('name'),
                new ContractorAddress(
                    new Street('name'),
                    new PostalCode('postal'),
                    new City('city'),
                    new Country('poland', 'PL')
                )
            ),
            new Currency('PLN'),
            new DateTime('2020-02-01'),
            new DateTime('2020-02-01'),
        );

        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <invoice>
            <contractor>
                <id>100</id>
            </contractor>
            <paymentmethod>transfer</paymentmethod>
            <currency>PLN</currency>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <date>2020-02-01</date>
            <paymentdate>2020-02-01</paymentdate>
            <description>Description Example</description>
        </invoice>
    </invoices>
</api>
XML,
            $invoice->print($this->media)->toString()
        );
        /*
        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <invoice>
            <contractor>
                <id>100</id>
            </contractor>
            <paymentmethod>transfer</paymentmethod>
            <currency>PLN</currency>
            <alreadypaid_initial>1400</alreadypaid_initial>
            <type>normal</type>
            <date>2020-02-01</date>
            <paymentdate>2020-02-01</paymentdate>
            <description>Description Example</description>
        </invoice>
    </invoices>
</api>
XML,
            $invoice->print($this->media)->getXML()
        );
        */
    }
}
