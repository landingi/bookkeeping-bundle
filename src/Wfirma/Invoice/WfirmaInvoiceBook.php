<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceBook;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItemCollection;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries\InvoiceSeriesIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\WFirmaInvoice;

final class WfirmaInvoiceBook implements InvoiceBook
{
    private WfirmaClient $client;

    public function __construct(WfirmaClient $client)
    {
        $this->client = $client;
    }

    /**
     * Currently returns fake invoice.
     */
    public function find(InvoiceIdentifier $identifier): Invoice
    {
        return new WFirmaInvoice(
            $identifier,
            new InvoiceSeries(new InvoiceSeriesIdentifier(700)),
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
            new DateTime(),
            new DateTime(),
            new Language('en')
        );
    }

    public function create(
        Contractor $contractor,
        InvoiceSeries $series,
        InvoiceDescription $description,
        InvoiceItemCollection $itemCollection
    ): Invoice {
        return new WFirmaInvoice(
            new InvoiceIdentifier('2'),
            $series,
            $description,
            $itemCollection,
            $contractor,
            new Currency('PLN'),
            new DateTime(),
            new DateTime(),
            new Language('en')
        );
    }

    public function delete(InvoiceIdentifier $identifier): void
    {
        $this->client->requestDELETE(sprintf('/invoices/delete/%s', $identifier->toString()));
    }
}
