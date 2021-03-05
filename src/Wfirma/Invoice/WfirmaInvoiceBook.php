<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceBook;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItemCollection;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;

final class WfirmaInvoiceBook implements InvoiceBook
{
    private WfirmaClient $client;

    public function __construct(WfirmaClient $client)
    {
        $this->client = $client;
    }

    public function find(InvoiceIdentifier $identifier): Invoice
    {
        // TODO: Implement find() method.
    }

    public function create(Contractor $contractor, InvoiceSeries $series, InvoiceDescription $description, InvoiceItemCollection $itemCollection): Invoice
    {
        // TODO: Implement create() method.
    }

    public function delete(InvoiceIdentifier $identifier): void
    {
        $this->client->requestDELETE(sprintf('/invoices/delete/%s', $identifier->toString()));
    }
}
