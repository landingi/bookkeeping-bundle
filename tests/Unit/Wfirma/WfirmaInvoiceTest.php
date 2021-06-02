<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries\InvoiceSeriesIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItemCollection;
use Landingi\BookkeepingBundle\Wfirma\WfirmaInvoice;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use PHPUnit\Framework\TestCase;

final class WfirmaInvoiceTest extends TestCase
{
    public function testItPrints(): void
    {
        $invoice = new WfirmaInvoice(
            new InvoiceIdentifier('2'),
            new InvoiceSeries(new InvoiceSeriesIdentifier(700)),
            new InvoiceDescription('Description Example'),
            new InvoiceFullNumber('FV 69/2021'),
            new InvoiceTotalValue(100),
            new WfirmaInvoiceItemCollection([
                new WfirmaInvoiceItem(
                    new Name('item name 1'),
                    new Price(10),
                    new WfirmaValueAddedTax('1111', new ValueAddedTax(20)),
                    new NumberOfUnits(1)
                ),
            ]),
            new Person(
                new ContractorIdentifier('100'),
                new ContractorName('name'),
                new ContractorEmail('bar@test.test'),
                new ContractorAddress(
                    new Street('name'),
                    new PostalCode('postal'),
                    new City('city'),
                    new Country('PL')
                )
            ),
            new Currency('USD'),
            new DateTime('2020-02-01'),
            new DateTime('2020-02-01'),
            new DateTime('2020-02-01'),
            new Language('en')
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
            <currency>USD</currency>
            <paid>1</paid>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <price_type>netto</price_type>
            <date>2020-02-01</date>
            <paymentdate>2020-02-01</paymentdate>
            <disposaldate>2020-02-01</disposaldate>
            <description>Description Example</description>
            <fullnumber>FV 69/2021</fullnumber>
            <total>1</total>
            <series>
                <id>700</id>
            </series>
            <translation_language>
                <id>1</id>
            </translation_language>
            <invoicecontents>
                <invoicecontent>
                    <name>item name 1</name>
                    <unit>szt.</unit>
                    <count>1</count>
                    <price>0.1</price>
                    <vat_code>
                        <id>1111</id>
                    </vat_code>
                </invoicecontent>
            </invoicecontents>
            <vat_moss_details>
                <type>SA</type>
                <evidence1_type>A</evidence1_type>
                <evidence2_type>F</evidence2_type>
            </vat_moss_details>
        </invoice>
    </invoices>
</api>
XML,
            $invoice->print(WfirmaMedia::api())->toString()
        );
    }

    public function testItPrintsPolish(): void
    {
        $invoice = new WfirmaInvoice(
            new InvoiceIdentifier('1'),
            new InvoiceSeries(new InvoiceSeriesIdentifier(700)),
            new InvoiceDescription('Description Example'),
            new InvoiceFullNumber('FV 99/2021'),
            new InvoiceTotalValue(100),
            new WfirmaInvoiceItemCollection([
                new WfirmaInvoiceItem(
                    new Name('item name 1'),
                    new Price(10),
                    new WfirmaValueAddedTax('1111', new ValueAddedTax(20)),
                    new NumberOfUnits(1)
                ),
            ]),
            new Person(
                new ContractorIdentifier('100'),
                new ContractorName('name'),
                new ContractorEmail('bar@test.test'),
                new ContractorAddress(
                    new Street('name'),
                    new PostalCode('postal'),
                    new City('city'),
                    new Country('PL')
                )
            ),
            new Currency('PLN'),
            new DateTime('2020-02-01'),
            new DateTime('2020-02-01'),
            new DateTime('2020-02-01'),
            new Language('pl')
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
            <paid>1</paid>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <price_type>netto</price_type>
            <date>2020-02-01</date>
            <paymentdate>2020-02-01</paymentdate>
            <disposaldate>2020-02-01</disposaldate>
            <description>Description Example</description>
            <fullnumber>FV 99/2021</fullnumber>
            <total>1</total>
            <series>
                <id>700</id>
            </series>
            <invoicecontents>
                <invoicecontent>
                    <name>item name 1</name>
                    <unit>szt.</unit>
                    <count>1</count>
                    <price>0.1</price>
                    <vat_code>
                        <id>1111</id>
                    </vat_code>
                </invoicecontent>
            </invoicecontents>
            <vat_moss_details>
                <type>SA</type>
                <evidence1_type>A</evidence1_type>
                <evidence2_type>F</evidence2_type>
            </vat_moss_details>
        </invoice>
    </invoices>
</api>
XML,
            $invoice->print(WfirmaMedia::api())->toString()
        );
    }

    /**
     * European company has no VAT rate
     */
    public function testItPrintsForEuropeanCompanyOutsidePoland(): void
    {
        $invoice = new WfirmaInvoice(
            new InvoiceIdentifier('1'),
            new InvoiceSeries(new InvoiceSeriesIdentifier(700)),
            new InvoiceDescription('Description Example'),
            new InvoiceFullNumber('FV 99/2021'),
            new InvoiceTotalValue(100),
            new WfirmaInvoiceItemCollection([
                new WfirmaInvoiceItem(
                    new Name('item name 1'),
                    new Price(10),
                    new WfirmaValueAddedTax(WfirmaValueAddedTax::NO_TAX, new ValueAddedTax(0)),
                    new NumberOfUnits(1)
                ),
            ]),
            new Company(
                new ContractorIdentifier('123'),
                new ContractorName('EUR FR Company Contractor'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('Parisian Street'),
                    new PostalCode('38330'),
                    new City('Paris'),
                    new Country('FR')
                ),
                new Company\ValueAddedTaxIdentifier('50844926014')
            ),
            new Currency('EUR'),
            new DateTime('2020-02-01'),
            new DateTime('2020-02-01'),
            new DateTime('2020-02-01'),
            new Language('pl')
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
            <paid>1</paid>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <price_type>netto</price_type>
            <date>2020-02-01</date>
            <paymentdate>2020-02-01</paymentdate>
            <disposaldate>2020-02-01</disposaldate>
            <description>Description Example</description>
            <fullnumber>FV 99/2021</fullnumber>
            <total>1</total>
            <series>
                <id>700</id>
            </series>
            <invoicecontents>
                <invoicecontent>
                    <name>item name 1</name>
                    <unit>szt.</unit>
                    <count>1</count>
                    <price>0.1</price>
                    <vat_code>NP</vat_code>
                </invoicecontent>
            </invoicecontents>
            <vat_moss_details>
                <type>SA</type>
                <evidence1_type>A</evidence1_type>
                <evidence2_type>F</evidence2_type>
            </vat_moss_details>
        </invoice>
    </invoices>
</api>
XML,
            $invoice->print(WfirmaMedia::api())->toString()
        );
    }
}
