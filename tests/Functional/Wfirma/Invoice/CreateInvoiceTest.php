<?php
declare(strict_types=1);

namespace Functional\Wfirma\Invoice;

use DateTime;
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

final class CreateInvoiceTest extends TestCase
{
    private ContractorBook $contractorBook;
    private InvoiceBook $invoiceBook;

    public function setUp(): void
    {
        $client = new WfirmaClient(
            new WfirmaCredentials(
                (string) getenv('WFIRMA_API_LOGIN'),
                (string) getenv('WFIRMA_API_PASSWORD'),
                (int) getenv('WFIRMA_API_COMPANY')
            )
        );

        $this->invoiceBook = new WfirmaInvoiceBook($client, new InvoiceFactory(), new ContractorFactory());
        $this->contractorBook = new WfirmaContractorBook($client, new ContractorFactory());
    }

    /**
     * Company contractor from EU pays in EUR
     */
    public function testCreateInvoiceCompany(): void
    {
        //CREATE CONTRACTOR
        $contractor = $this->contractorBook->create(
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
                new Company\ValueAddedTaxIdentifier('FR50844926014')
            )
        );

        self::assertNotEmpty($contractor->getIdentifier()->toString());

        //CREATE INVOICE
        $invoice = $this->invoiceBook->create(
            new WfirmaInvoice(
                new InvoiceIdentifier('123'),
                new InvoiceSeries(new InvoiceSeries\InvoiceSeriesIdentifier(0)),
                new InvoiceDescription('test description - bundle invoice'),
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
                new DateTime(),
                new DateTime(),
                new DateTime(),
                new Language('PL')
            )
        );

        self::assertNotEmpty($invoice->getIdentifier()->toString());

        //test find
        $invoice = $this->invoiceBook->find($invoice->getIdentifier());

        //test delete
        $this->invoiceBook->delete($invoice->getIdentifier());
        $this->contractorBook->delete($contractor->getIdentifier());
    }
}
