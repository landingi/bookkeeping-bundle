<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use JsonException;
use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceBook;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClientException;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceFactory;
use Landingi\BookkeepingBundle\Wfirma\WfirmaException;
use Landingi\BookkeepingBundle\Wfirma\WfirmaInvoiceCollection;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use function sprintf;

final class WfirmaInvoiceBook implements InvoiceBook
{
    private const INVOICE_API_URL = '/invoices/%s';
    private const INVOICES_FIND_URL = '/invoices/find';

    private WfirmaClient $client;
    private InvoiceFactory $invoiceFactory;
    private ContractorFactory $contractorFactory;

    public function __construct(
        WfirmaClient $client,
        InvoiceFactory $invoiceFactory,
        ContractorFactory $contractorFactory
    ) {
        $this->client = $client;
        $this->invoiceFactory = $invoiceFactory;
        $this->contractorFactory = $contractorFactory;
    }

    /**
     * @throws Contractor\ContractorException
     * @throws WfirmaException
     * @throws JsonException
     * @throws WfirmaClientException
     */
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

    public function list(int $page, Condition ...$conditions): Collection
    {
        $result = $this->client->findInvoices(self::INVOICES_FIND_URL, $page, ...$conditions);
        $invoiceCollection = new WfirmaInvoiceCollection(
            array_map(
                function (array $invoiceResult) {
                    return $this->invoiceFactory->getInvoiceFromApiData(
                        $invoiceResult['invoice'],
                        $this->contractorFactory->getContractor($invoiceResult['invoice'])
                    );
                },
                array_filter($result['invoices'], static function (array $invoiceResult) {
                    return false === empty($invoiceResult['invoice']);
                })
            )
        );

        if ($result['invoices']['parameters']['total'] > $page * (int) $result['invoices']['parameters']['limit']) {
            $invoiceCollection->merge($this->list($page + 1, ...$conditions));
        }

        return $invoiceCollection;
    }

    /**
     * @throws \JsonException
     * @throws \Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException
     * @throws \Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClientException
     * @throws \Landingi\BookkeepingBundle\Wfirma\WfirmaException
     */
    public function create(Invoice $invoice): Invoice
    {
        $invoiceResult = $this->getInvoiceResult(
            $this->client->requestPOST(
                sprintf(
                    self::INVOICE_API_URL,
                    'add'
                ),
                $invoice->print(WfirmaMedia::api())->toString()
            )
        );

        return $this->invoiceFactory->getInvoiceFromApiData(
            $invoiceResult,
            $this->contractorFactory->getContractor($this->getContractorResult($invoiceResult))
        );
    }

    /**
     * @throws JsonException
     * @throws WfirmaClientException
     */
    public function delete(InvoiceIdentifier $identifier): void
    {
        $this->client->requestDELETE(sprintf('/invoices/delete/%s', $identifier->toString()));
    }

    /**
     * @throws WfirmaClientException
     */
    public function download(InvoiceIdentifier $identifier): string
    {
        return $this->client->requestInvoiceDownload(
            sprintf(
                self::INVOICE_API_URL,
                sprintf('%s%s', 'download/', $identifier->toString())
            )
        );
    }

    private function getContractorResult(array $response): array
    {
        return [
            'id' => $response['contractor']['id'],
            'name' => $response['contractor_detail']['name'],
            'email' => $response['contractor']['email'],
            'nip' => $response['contractor_detail']['nip'],
            'street' => $response['contractor_detail']['street'],
            'city' => $response['contractor_detail']['city'],
            'zip' => $response['contractor_detail']['zip'],
            'country' => $response['contractor_detail']['country'],
        ];
    }

    /**
     * @throws WfirmaException
     */
    private function getInvoiceResult(array $response): array
    {
        if (false === isset($response['invoices'][0]['invoice'])) {
            throw new WfirmaException('Invalid response structure!');
        }

        return $response['invoices'][0]['invoice'];
    }
}
