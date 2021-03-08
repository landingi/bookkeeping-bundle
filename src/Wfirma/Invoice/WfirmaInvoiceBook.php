<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceBook;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Wfirma\Client\Request\Invoice\Download;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceFactory;
use Landingi\BookkeepingBundle\Wfirma\WfirmaException;
use Landingi\BookkeepingBundle\Wfirma\WfirmaInvoice;

final class WfirmaInvoiceBook implements InvoiceBook
{
    private const INVOICE_API_URL = 'http://api2.wfirma.pl/invoices/%s';

    private WfirmaClient $client;
    private InvoiceFactory $invoiceFactory;
    private ContractorFactory $contractorFactory;

    public function __construct(WfirmaClient $client)
    {
        $this->client = $client;
    }

    public function find(InvoiceIdentifier $identifier): Invoice
    {
        $invoiceResult = $this->getInvoiceResult(
            $this->client->requestGET(
                sprintf(
                    self::INVOICE_API_URL,
                    sprintf('%s%s', 'get/', $identifier->toString())
                )
            )
        );

        return $this->invoiceFactory->getInvoiceFromApiData(
            $invoiceResult,
            $this->contractorFactory->getContractor($this->getContractorResult($invoiceResult))
        );
    }

    public function create(
        Contractor $contractor,
        InvoiceSeries $series,
        InvoiceDescription $description,
        Collection $itemCollection
    ): Invoice {
        $this->client->getVatId('', 23);

        return new WfirmaInvoice(
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

    private function getContractorResult(array $response): array
    {
        return [
            'id' => $response['contractor']['id'],
            'name' => $response['contractor']['name'],
            'email' => $response['contractor']['email'],
            'nip' => $response['contractor_detail']['nip'],
            'street' => $response['contractor_detail']['street'],
            'city' => $response['contractor_detail']['city'],
            'zip' => $response['contractor_detail']['zip'],
            'country' => $response['contractor_detail']['country'],
        ];
    }

    private function getInvoiceResult(array $response): array
    {
        if (false === isset($response['invoices'][0]['invoice'])) {
            throw new WfirmaException('Invalid response structure!');
        }

        return $response['invoices'][0]['invoice'];
    }

    public function download(InvoiceIdentifier $identifier): string
    {
        return $this->client->requestInvoiceDownload(
            sprintf(self::INVOICE_API_URL,
                sprintf('%s%s', 'download/', $identifier->toString())
            )
        );
    }
}
