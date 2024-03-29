<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Wfirma\Invoice;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExactDate;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExcludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\IncludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceBook;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use Landingi\BookkeepingBundle\Bookkeeping\InvoiceSummary;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax\MemoryIdentifierFactory;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaApiUrl;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaConditionTransformer;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\WfirmaContractorBook;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceSummaryFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceBook;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItemCollection;
use Landingi\BookkeepingBundle\Wfirma\WfirmaInvoice;

final class WfirmaInvoiceBookTest extends IntegrationTestCase
{
    private Contractor\ContractorBook $contractorBook;
    private InvoiceBook $invoiceBook;

    public function setUp(): void
    {
        $client = new WfirmaClient(
            new WfirmaApiUrl((string) getenv('WFIRMA_API_URL')),
            new WfirmaCredentials(
                (string) getenv('WFIRMA_API_ACCESS_KEY'),
                (string) getenv('WFIRMA_API_SECRET_KEY'),
                (string) getenv('WFIRMA_API_APP_KEY'),
                (int) getenv('WFIRMA_API_COMPANY_ID')
            ),
            new WfirmaConditionTransformer()
        );
        $factory = new ContractorFactory(
            new MemoryIdentifierFactory()
        );
        $this->invoiceBook = new WfirmaInvoiceBook(
            $client,
            new InvoiceFactory(),
            new InvoiceSummaryFactory(),
            $factory
        );
        $this->contractorBook = new WfirmaContractorBook($client, $factory);
    }

    public function testInvoiceWorkflow(): void
    {
        $contractor = $this->getContractor();
        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('test description - bundle invoice'),
                new InvoiceFullNumber('FV 69/2023'),
                new InvoiceTotalValue(100),
                new InvoiceNetPlnValue(100),
                new WfirmaInvoiceItemCollection([
                    new WfirmaInvoiceItem(
                        new Name('foo 1'),
                        new Price((int) (100.55 * 100)),
                        new WfirmaValueAddedTax('222', new ValueAddedTax(23)),
                        new NumberOfUnits(2)
                    ),
                ]),
                $contractor,
                new Currency('PLN'),
                $now = new DateTime(),
                $now,
                $now,
                new Language('PL')
            )
        );

        $this->assertNotEmpty($invoice->getIdentifier()->toString());

        //test find
        $invoice = $this->invoiceBook->find($invoice->getIdentifier());
        $this->assertEquals('test description - bundle invoice', (string) $invoice->getDescription());
        $this->assertEquals(247.35, $invoice->getTotalValue()->toFloat());
        $this->assertEquals(201.10, $invoice->getNetPlnValue()->toFloat());
        $this->assertEquals(201.10, $invoice->getMoneyValue());

        //test list
        $conditions = [
            new ExactDate(\DateTimeImmutable::createFromMutable($now)),
            new IncludeSeries((string) $invoice->getFullNumber()),
        ];
        $invoices = $this->invoiceBook->list(1, ...$conditions);
        $summaries = $this->invoiceBook->listSummaries(1, ...$conditions);
        $this->assertCount(1, $invoiceArray = $invoices->getAll());
        $this->assertCount(1, $summaryArray = $summaries->getAll());
        /** @var WfirmaInvoice $lastInvoice */
        $lastInvoice = end($invoiceArray);
        /** @var InvoiceSummary $lastSummary */
        $lastSummary = end($summaryArray);
        $this->assertEquals('test description - bundle invoice', (string) $lastInvoice->getDescription());
        $this->assertEquals($invoice->getIdentifier(), $lastInvoice->getIdentifier());
        $this->assertEquals($invoice->getIdentifier(), $lastSummary->getIdentifier());

        // test list excludes invoice
        $conditions = [
            new ExactDate(\DateTimeImmutable::createFromMutable($now)),
            new ExcludeSeries((string) $invoice->getFullNumber()),
        ];
        $invoices = $this->invoiceBook->list(1, ...$conditions);
        $this->assertEmpty(array_filter(
            $invoices->getAll(),
            fn (Invoice $result) => (string) $result->getFullNumber() === (string) $invoice->getFullNumber()
        ));

        //test delete
        $this->invoiceBook->delete($invoice->getIdentifier());
        $this->contractorBook->delete($contractor->getIdentifier());
    }

    private function getContractor(): Contractor
    {
        return $this->contractorBook->create(
            new Person(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('111-111'),
                    new City('test'),
                    new Country('PL')
                )
            )
        );
    }
}
