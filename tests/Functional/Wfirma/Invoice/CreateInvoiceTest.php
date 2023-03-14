<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Functional\Wfirma\Invoice;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceBook;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax\MemoryIdentifierFactory;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\WfirmaContractorBook;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceBook;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItemCollection;
use Landingi\BookkeepingBundle\Wfirma\WfirmaInvoice;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use PHPUnit\Framework\TestCase;

final class CreateInvoiceTest extends TestCase
{
    private ContractorBook $contractorBook;
    private InvoiceBook $invoiceBook;
    private DateTime $today;

    public function setUp(): void
    {
        $client = new WfirmaClient(
            new WfirmaCredentials(
                (string) getenv('WFIRMA_API_LOGIN'),
                (string) getenv('WFIRMA_API_PASSWORD'),
                (int) getenv('WFIRMA_API_COMPANY')
            )
        );
        $factory = new ContractorFactory(
            new MemoryIdentifierFactory()
        );
        $this->invoiceBook = new WfirmaInvoiceBook($client, new InvoiceFactory(), $factory);
        $this->contractorBook = new WfirmaContractorBook($client, $factory);
        $this->today = new DateTime();
    }

    private function cleanUp(Invoice $invoice, Contractor $contractor): void
    {
        $this->invoiceBook->delete($invoice->getIdentifier());
        $this->contractorBook->delete($contractor->getIdentifier());
    }

    /**
     * Case 1.1.
     *
     * VAT 23%
     * MOSS not applicable
     */
    public function testCompanyInPoland(): void
    {
        $contractor = $this->contractorBook->create(
            new Company(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('test'),
                    new Country('PL')
                ),
                new SimpleIdentifier('6762461659')
            )
        );
        $contractorRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>test foo</name>
            <altname>test foo</altname>
            <street>test 123</street>
            <zip>11-111</zip>
            <city>test</city>
            <country>PL</country>
            <email>test@landingi.com</email>
            <tax_id_type>nip</tax_id_type>
            <nip>6762461659</nip>
        </contractor>
    </contractors>
</api>
XML;
        self::assertXmlStringEqualsXmlString($contractorRequest, $contractor->print(WfirmaMedia::api())->toString());

        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('testCompanyInPoland'),
                new InvoiceFullNumber('FV 69/2021'),
                new InvoiceTotalValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax('0', new ValueAddedTax(23)),
                        new NumberOfUnits(2)
                    ),
                ]),
                $contractor,
                new Currency('PLN'),
                $this->today,
                $this->today,
                $this->today,
                new Language('PL')
            )
        );
        $invoiceRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
   <invoices>
      <invoice>
         <contractor>
            <id>{$contractor->getIdentifier()->toString()}</id>
         </contractor>
         <paymentmethod>transfer</paymentmethod>
         <currency>PLN</currency>
         <paid>1</paid>
         <alreadypaid_initial>0</alreadypaid_initial>
         <type>normal</type>
         <price_type>netto</price_type>
         <date>{$this->today->format('Y-m-d')}</date>
         <paymentdate>{$this->today->format('Y-m-d')}</paymentdate>
         <disposaldate>{$this->today->format('Y-m-d')}</disposaldate>
         <description>testCompanyInPoland</description>
         <fullnumber>{$invoice->getFullNumber()->toString()}</fullnumber>
         <total>247.35</total>
         <series>
            <id>2539307</id>
         </series>
         <translation_language>
            <id>1</id>
         </translation_language>
         <invoicecontents>
            <invoicecontent>
               <name>foo 1</name>
               <unit>szt.</unit>
               <count>2</count>
               <price>100.55</price>
               <vat_code>
                  <id>222</id>
               </vat_code>
            </invoicecontent>
         </invoicecontents>
      </invoice>
   </invoices>
</api>
XML;
        self::assertXmlStringEqualsXmlString($invoiceRequest, $invoice->print(WfirmaMedia::api())->toString());
        $this->cleanUp($invoice, $contractor);
    }

    /**
     * Case 1.2.
     *
     * VAT NP - It is not subject to VAT
     * MOSS not applicable
     */
    public function testCompanyInEuropeanUnion(): void
    {
        $contractor = $this->contractorBook->create(
            new Company(
                new ContractorIdentifier('222'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('Paris'),
                    new Country('FR')
                ),
                new Company\ValueAddedTax\ValidatedIdentifier(
                    new Company\ValueAddedTax\SimpleIdentifier('47534386818'),
                    new Country('FR')
                )
            )
        );
        $contractorRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>test foo</name>
            <altname>test foo</altname>
            <street>test 123</street>
            <zip>11-111</zip>
            <city>Paris</city>
            <country>FR</country>
            <email>test@landingi.com</email>
            <tax_id_type>vat</tax_id_type>
            <nip>FR47534386818</nip>
        </contractor>
    </contractors>
</api>
XML;
        self::assertXmlStringEqualsXmlString($contractorRequest, $contractor->print(WfirmaMedia::api())->toString());

        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('testCompanyInEuropeanUnion'),
                new InvoiceFullNumber('FV 69/2021'),
                new InvoiceTotalValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax(WfirmaValueAddedTax::NO_TAX, new ValueAddedTax(0)),
                        new NumberOfUnits(2)
                    ),

                ]),
                $contractor,
                new Currency('EUR'),
                $this->today,
                $this->today,
                $this->today,
                new Language('EN')
            )
        );
        $invoiceRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <invoice>
            <contractor>
                <id>{$contractor->getIdentifier()->toString()}</id>
            </contractor>
            <paymentmethod>transfer</paymentmethod>
            <currency>EUR</currency>
            <paid>1</paid>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <price_type>netto</price_type>
            <date>{$this->today->format('Y-m-d')}</date>
            <paymentdate>{$this->today->format('Y-m-d')}</paymentdate>
            <disposaldate>{$this->today->format('Y-m-d')}</disposaldate>
            <description>testCompanyInEuropeanUnion</description>
            <fullnumber>{$invoice->getFullNumber()->toString()}</fullnumber>
            <total>201.1</total>
            <series>
                <id>2539307</id>
            </series>
            <translation_language>
                <id>1</id>
            </translation_language>
            <invoicecontents>
                <invoicecontent>
                    <name>foo 1</name>
                    <unit>szt.</unit>
                    <count>2</count>
                    <price>100.55</price>
                    <vat_code>
                        <id>230</id>
                    </vat_code>
                </invoicecontent>
            </invoicecontents>
        </invoice>
    </invoices>
</api>
XML;
        self::assertXmlStringEqualsXmlString($invoiceRequest, $invoice->print(WfirmaMedia::api())->toString());
        $this->cleanUp($invoice, $contractor);
    }

    /**
     * Case 1.3 UK/GB.
     *
     * VAT NP - It is not subject to VAT
     * MOSS not applicable
     */
    public function testCompanyInGreatBritain(): void
    {
        $contractor = $this->contractorBook->create(
            new Company(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('London'),
                    new Country('GB')
                ),
                new Company\ValueAddedTax\SimpleIdentifier('550844926014')
            )
        );
        $contractorRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>test foo</name>
            <altname>test foo</altname>
            <street>test 123</street>
            <zip>11-111</zip>
            <city>London</city>
            <country>GB</country>
            <email>test@landingi.com</email>
            <tax_id_type>custom</tax_id_type>
            <nip>GB550844926014</nip>
        </contractor>
    </contractors>
</api>
XML;
        self::assertXmlStringEqualsXmlString($contractorRequest, $contractor->print(WfirmaMedia::api())->toString());

        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('testCompanyInGreatBritain'),
                new InvoiceFullNumber('FV 69/2021'),
                new InvoiceTotalValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax(WfirmaValueAddedTax::NO_TAX, new ValueAddedTax(0)),
                        new NumberOfUnits(2)
                    ),
                ]),
                $contractor,
                new Currency('GBP'),
                $this->today,
                $this->today,
                $this->today,
                new Language('EN')
            )
        );
        $invoiceRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
   <invoices>
      <invoice>
         <contractor>
            <id>{$contractor->getIdentifier()->toString()}</id>
         </contractor>
         <paymentmethod>transfer</paymentmethod>
         <currency>GBP</currency>
         <paid>1</paid>
         <alreadypaid_initial>0</alreadypaid_initial>
         <type>normal</type>
         <price_type>netto</price_type>
         <date>{$this->today->format('Y-m-d')}</date>
         <paymentdate>{$this->today->format('Y-m-d')}</paymentdate>
         <disposaldate>{$this->today->format('Y-m-d')}</disposaldate>
         <description>testCompanyInGreatBritain</description>
         <fullnumber>{$invoice->getFullNumber()->toString()}</fullnumber>
         <total>201.1</total>
         <series>
            <id>2539307</id>
         </series>
         <translation_language>
            <id>1</id>
         </translation_language>
         <invoicecontents>
            <invoicecontent>
               <name>foo 1</name>
               <unit>szt.</unit>
               <count>2</count>
               <price>100.55</price>
               <vat_code>
                   <id>230</id>
               </vat_code>
            </invoicecontent>
         </invoicecontents>
      </invoice>
   </invoices>
</api>
XML;
        self::assertXmlStringEqualsXmlString($invoiceRequest, $invoice->print(WfirmaMedia::api())->toString());
        $this->cleanUp($invoice, $contractor);
    }

    /**
     * Case 1.3.
     *
     * VAT NP - It is not subject to VAT
     * MOSS not applicable
     */
    public function testCompanyInTheWorld(): void
    {
        $contractor = $this->contractorBook->create(
            new Company(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('New York'),
                    new Country('US')
                ),
                new Company\ValueAddedTax\SimpleIdentifier('333444555')
            )
        );
        //the <tax_id_type>custom</tax_id_type> is sent, but not retrieved
        $contractorRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>test foo</name>
            <altname>test foo</altname>
            <street>test 123</street>
            <zip>11-111</zip>
            <city>New York</city>
            <country>US</country>
            <email>test@landingi.com</email>
            <tax_id_type>custom</tax_id_type>
            <nip>US333444555</nip>
        </contractor>
    </contractors>
</api>
XML;
        self::assertXmlStringEqualsXmlString($contractorRequest, $contractor->print(WfirmaMedia::api())->toString());

        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('testCompanyInTheWorld'),
                new InvoiceFullNumber('FV 69/2021'),
                new InvoiceTotalValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax(WfirmaValueAddedTax::NO_TAX, new ValueAddedTax(0)),
                        new NumberOfUnits(2)
                    ),
                ]),
                $contractor,
                new Currency('EUR'),
                $this->today,
                $this->today,
                $this->today,
                new Language('EN')
            )
        );
        $invoiceRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
   <invoices>
      <invoice>
         <contractor>
            <id>{$contractor->getIdentifier()->toString()}</id>
         </contractor>
         <paymentmethod>transfer</paymentmethod>
         <currency>EUR</currency>
         <paid>1</paid>
         <alreadypaid_initial>0</alreadypaid_initial>
         <type>normal</type>
         <price_type>netto</price_type>
         <date>{$this->today->format('Y-m-d')}</date>
         <paymentdate>{$this->today->format('Y-m-d')}</paymentdate>
         <disposaldate>{$this->today->format('Y-m-d')}</disposaldate>
         <description>testCompanyInTheWorld</description>
         <fullnumber>{$invoice->getFullNumber()->toString()}</fullnumber>
         <total>201.1</total>
         <series>
            <id>2539307</id>
         </series>
         <translation_language>
            <id>1</id>
         </translation_language>
         <invoicecontents>
            <invoicecontent>
               <name>foo 1</name>
               <unit>szt.</unit>
               <count>2</count>
               <price>100.55</price>
               <vat_code>
                   <id>230</id>
               </vat_code>
            </invoicecontent>
         </invoicecontents>
      </invoice>
   </invoices>
</api>
XML;
        self::assertXmlStringEqualsXmlString($invoiceRequest, $invoice->print(WfirmaMedia::api())->toString());
        $this->cleanUp($invoice, $contractor);
    }

    /**
     * Case 2.1.
     *
     * VAT 23%
     * MOSS not applicable
     */
    public function testPersonInPoland(): void
    {
        $contractor = $this->contractorBook->create(
            new Person(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('test'),
                    new Country('PL')
                )
            )
        );
        $contractorRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>test foo</name>
            <altname>test foo</altname>
            <street>test 123</street>
            <zip>11-111</zip>
            <city>test</city>
            <country>PL</country>
            <email>test@landingi.com</email>
        </contractor>
    </contractors>
</api>
XML;
        self::assertXmlStringEqualsXmlString($contractorRequest, $contractor->print(WfirmaMedia::api())->toString());

        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('testPersonInPoland'),
                new InvoiceFullNumber('FV 69/2021'),
                new InvoiceTotalValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax('0', new ValueAddedTax(23)),
                        new NumberOfUnits(2)
                    ),
                ]),
                $contractor,
                new Currency('PLN'),
                $this->today,
                $this->today,
                $this->today,
                new Language('PL')
            )
        );
        $invoiceRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <invoice>
            <contractor>
                <id>{$contractor->getIdentifier()->toString()}</id>
            </contractor>
            <paymentmethod>transfer</paymentmethod>
            <currency>PLN</currency>
            <paid>1</paid>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <price_type>netto</price_type>
            <date>{$this->today->format('Y-m-d')}</date>
            <paymentdate>{$this->today->format('Y-m-d')}</paymentdate>
            <disposaldate>{$this->today->format('Y-m-d')}</disposaldate>
            <description>testPersonInPoland</description>
            <fullnumber>{$invoice->getFullNumber()->toString()}</fullnumber>
            <total>247.35</total>
            <series>
                <id>2539307</id>
            </series>
            <translation_language>
                <id>1</id>
            </translation_language>
            <invoicecontents>
                <invoicecontent>
                    <name>foo 1</name>
                    <unit>szt.</unit>
                    <count>2</count>
                    <price>100.55</price>
                    <vat_code>
                        <id>222</id>
                    </vat_code>
                </invoicecontent>
            </invoicecontents>
        </invoice>
   </invoices>
</api>
XML;
        self::assertXmlStringEqualsXmlString($invoiceRequest, $invoice->print(WfirmaMedia::api())->toString());
        $this->cleanUp($invoice, $contractor);
    }

    /**
     * Case 2.2.
     *
     * VAT rate from the country person is from
     * MOSS is applicable
     */
    public function testPersonInEuropeanUnion(): void
    {
        $contractor = $this->contractorBook->create(
            new Person(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('test'),
                    new Country('DE')
                )
            )
        );
        $contractorRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>test foo</name>
            <altname>test foo</altname>
            <street>test 123</street>
            <zip>11-111</zip>
            <city>test</city>
            <country>DE</country>
            <email>test@landingi.com</email>
        </contractor>
    </contractors>
</api>
XML;
        self::assertXmlStringEqualsXmlString($contractorRequest, $contractor->print(WfirmaMedia::api())->toString());

        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('testPersonInEuropeanUnion'),
                new InvoiceFullNumber('FV 69/2021'),
                new InvoiceTotalValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax('617', new ValueAddedTax(19)),
                        new NumberOfUnits(2)
                    ),
                ]),
                $contractor,
                new Currency('EUR'),
                $this->today,
                $this->today,
                $this->today,
                new Language('EN')
            )
        );
        $invoiceRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <invoice>
            <contractor>
                <id>{$contractor->getIdentifier()->toString()}</id>
            </contractor>
            <paymentmethod>transfer</paymentmethod>
            <currency>EUR</currency>
            <paid>1</paid>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <price_type>netto</price_type>
            <date>{$this->today->format('Y-m-d')}</date>
            <paymentdate>{$this->today->format('Y-m-d')}</paymentdate>
            <disposaldate>{$this->today->format('Y-m-d')}</disposaldate>
            <description>testPersonInEuropeanUnion</description>
            <fullnumber>{$invoice->getFullNumber()->toString()}</fullnumber>
            <total>239.31</total>
            <series>
                <id>2539307</id>
            </series>
            <translation_language>
                <id>1</id>
            </translation_language>
            <invoicecontents>
                <invoicecontent>
                    <name>foo 1</name>
                    <unit>szt.</unit>
                    <count>2</count>
                    <price>100.55</price>
                    <vat_code>
                        <id>617</id>
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
XML;
        self::assertXmlStringEqualsXmlString($invoiceRequest, $invoice->print(WfirmaMedia::api())->toString());
        $this->cleanUp($invoice, $contractor);
    }

    /**
     * Case 2.3.
     *
     * VAT NP - It is not subject to VAT
     * MOSS not applicable
     */
    public function testPersonInTheWorld(): void
    {
        $contractor = $this->contractorBook->create(
            new Person(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('New York'),
                    new Country('US')
                )
            )
        );
        $contractorRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>test foo</name>
            <altname>test foo</altname>
            <street>test 123</street>
            <zip>11-111</zip>
            <city>New York</city>
            <country>US</country>
            <email>test@landingi.com</email>
        </contractor>
    </contractors>
</api>
XML;
        self::assertXmlStringEqualsXmlString($contractorRequest, $contractor->print(WfirmaMedia::api())->toString());

        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('testPersonInTheWorld'),
                new InvoiceFullNumber('FV 69/2021'),
                new InvoiceTotalValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax('NP', new ValueAddedTax(0)),
                        new NumberOfUnits(2)
                    ),
                ]),
                $contractor,
                new Currency('USD'),
                $this->today,
                $this->today,
                $this->today,
                new Language('EN')
            )
        );
        $invoiceRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <invoice>
            <contractor>
                <id>{$contractor->getIdentifier()->toString()}</id>
            </contractor>
            <paymentmethod>transfer</paymentmethod>
            <currency>USD</currency>
            <paid>1</paid>
            <alreadypaid_initial>0</alreadypaid_initial>
            <type>normal</type>
            <price_type>netto</price_type>
            <date>{$this->today->format('Y-m-d')}</date>
            <paymentdate>{$this->today->format('Y-m-d')}</paymentdate>
            <disposaldate>{$this->today->format('Y-m-d')}</disposaldate>
            <description>testPersonInTheWorld</description>
            <fullnumber>{$invoice->getFullNumber()->toString()}</fullnumber>
            <total>201.1</total>
            <series>
                <id>2539307</id>
            </series>
            <translation_language>
                <id>1</id>
            </translation_language>
            <invoicecontents>
                <invoicecontent>
                    <name>foo 1</name>
                    <unit>szt.</unit>
                    <count>2</count>
                    <price>100.55</price>
                    <vat_code>
                        <id>230</id>
                    </vat_code>
                </invoicecontent>
            </invoicecontents>
        </invoice>
   </invoices>
</api>
XML;
        self::assertXmlStringEqualsXmlString($invoiceRequest, $invoice->print(WfirmaMedia::api())->toString());
        $this->cleanUp($invoice, $contractor);
    }
}
