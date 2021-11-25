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
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
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
use PHPUnit\Framework\TestCase;

class DownloadInvoiceTest extends TestCase
{
    private WfirmaClient $client;
    private ContractorBook $contractorBook;
    private InvoiceBook $invoiceBook;
    private DateTime $today;

    public function setUp(): void
    {
        $this->client = new WfirmaClient(
            new WfirmaCredentials(
                (string) getenv('WFIRMA_API_LOGIN'),
                (string) getenv('WFIRMA_API_PASSWORD'),
                (int) getenv('WFIRMA_API_COMPANY')
            )
        );
        $this->invoiceBook = new WfirmaInvoiceBook($this->client, new InvoiceFactory(), new ContractorFactory());
        $this->contractorBook = new WfirmaContractorBook($this->client, new ContractorFactory());
        $this->today = new DateTime();
    }

    public function testDownloadInvoice(): void
    {
        // Arrange
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
                new Company\ValueAddedTaxIdentifier('6482791634')
            )
        );
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

        // Act
        $invoiceFile = base64_encode($this->client->requestInvoiceDownload((string) $invoice->getIdentifier()));

        //Assert
        $this->assertIsString($invoiceFile);
        $this->assertNotEmpty($invoiceFile);

        $this->cleanUp($invoice, $contractor);
    }

    private function cleanUp(Invoice $invoice, Contractor $contractor): void
    {
        $this->invoiceBook->delete($invoice->getIdentifier());
        $this->contractorBook->delete($contractor->getIdentifier());
    }
}
