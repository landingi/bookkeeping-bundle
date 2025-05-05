<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

use JsonException;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\InvoiceCondition;
use Landingi\BookkeepingBundle\Curl\Curl;
use Landingi\BookkeepingBundle\Curl\CurlException;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\AuthorizationException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\ErrorResponseException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\FatalException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\NotFoundException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\OutOfServiceException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalExecutionTimeLimitExceededException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalRequestsLimitExceededException;
use Landingi\BookkeepingBundle\Wfirma\Client\Request\Expenses\FindExpenses;
use Landingi\BookkeepingBundle\Wfirma\Client\Request\Invoice\Download;
use Landingi\BookkeepingBundle\Wfirma\Client\Request\Invoices\FindInvoices;
use Landingi\BookkeepingBundle\Wfirma\WFirmaLogger;
use function json_decode;
use function sprintf;

final class WfirmaClient
{
    private const DECLARATION_COUNTRIES_URL = 'declaration_countries/find';
    private const VAT_CODES_URL = 'vat_codes/find';

    private WfirmaApiUrl $wfirmaApiUrl;
    private WfirmaCredentials $credentials;
    private WfirmaConditionTransformer $conditionTransformer;
    private ?WFirmaLogger $logger;

    public function __construct(
        WfirmaApiUrl $wfirmaApiUrl,
        WfirmaCredentials $credentials,
        WfirmaConditionTransformer $conditionTransformer,
        ?WFirmaLogger $logger = null
    ) {
        $this->wfirmaApiUrl = $wfirmaApiUrl;
        $this->credentials = $credentials;
        $this->conditionTransformer = $conditionTransformer;
        $this->logger = $logger;
    }

    /**
     * @throws JsonException
     * @throws CurlException
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
     * @throws TotalRequestsLimitExceededException
     * @throws TotalExecutionTimeLimitExceededException
     * @throws ErrorResponseException
     */
    public function requestGET(string $url): array
    {
        $this->logWFirmaRequest($url);

        return $this->handleResponse(json_decode($this->getCurl($url)->requestGET(), true, 512, JSON_THROW_ON_ERROR), $url);
    }

    /**
     * @throws JsonException
     * @throws CurlException
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
     * @throws TotalRequestsLimitExceededException
     * @throws TotalExecutionTimeLimitExceededException
     * @throws ErrorResponseException
     */
    public function requestPOST(string $url, string $data): array
    {
        $this->logWFirmaRequest($url, $data);

        return $this->handleResponse(json_decode($this->getCurl($url)->requestPOST($data), true, 512, JSON_THROW_ON_ERROR), $url);
    }

    /**
     * @throws JsonException
     * @throws CurlException
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
     * @throws TotalRequestsLimitExceededException
     * @throws TotalExecutionTimeLimitExceededException
     * @throws ErrorResponseException
     */
    public function requestDELETE(string $url): array
    {
        $this->logWFirmaRequest($url);

        return $this->handleResponse(json_decode($this->getCurl($url)->requestDELETE(), true, 512, JSON_THROW_ON_ERROR), $url);
    }

    /**
     * @throws JsonException
     * @throws CurlException
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
     * @throws TotalRequestsLimitExceededException
     * @throws TotalExecutionTimeLimitExceededException
     * @throws WfirmaClientException
     */
    public function getVatId(string $countryCode, float $vatRate): int
    {
        /*
         * WFirma API does not recognize PL VAT tax, but the VAT id is 0
         */
        if ('PL' === $countryCode) {
            return 0;
        }

        $country = $this->requestPOST(
            self::DECLARATION_COUNTRIES_URL,
            (string) (new Request\DeclarationCountries\Find($countryCode))
        );

        $this->logWFirmaRequest(self::DECLARATION_COUNTRIES_URL);

        if (empty($country['declaration_countries'][0])) {
            throw new WfirmaClientException(
                self::DECLARATION_COUNTRIES_URL,
                $country,
                'declaration_countries',
                'Declaration Country not found'
            );
        }

        $vatCode = $this->requestPOST(
            self::VAT_CODES_URL,
            (string) (new Request\VatCodes\Find(
                (int) $country['declaration_countries'][0]['declaration_country']['id'],
                $vatRate
            ))
        );

        $this->logWFirmaRequest(self::VAT_CODES_URL);

        if (empty($vatCode['vat_codes'][0])) {
            throw new WfirmaClientException(
                self::VAT_CODES_URL,
                $vatCode,
                'vat_codes',
                'Vat Code Not Found'
            );
        }

        return (int) $vatCode['vat_codes'][0]['vat_code']['id'];
    }

    /**
     * @throws JsonException
     * @throws CurlException
     * @throws NotFoundException
     * @throws WfirmaClientException
     */
    public function requestInvoiceDownload(string $url): string
    {
        $this->logWFirmaRequest($url);

        return $this->handleFileResponse($this->getCurl($url)->requestPOST((string) new Download()), $url, 'invoice_download');
    }

    public function findInvoices(string $url, int $page = 1, InvoiceCondition ...$conditions): array
    {
        $this->logWFirmaRequest($url);

        return $this->requestPOST($url, (string) new FindInvoices(
            array_reduce(
                $conditions,
                function (string $carry, InvoiceCondition $condition) {
                    return <<<XML
                    {$carry}
                    {$this->conditionTransformer->toXml($condition)}
                    XML;
                },
                ''
            ),
            $page
        ));
    }

    public function findExpenses(string $url, int $page = 1, ExpenseCondition ...$conditions): array
    {
        $this->logWFirmaRequest($url);

        return $this->requestPOST($url, (string) new FindExpenses(
            array_reduce(
                $conditions,
                function (string $carry, ExpenseCondition $condition) {
                    return <<<XML
                    {$carry}
                    {$this->conditionTransformer->toXml($condition)}
                    XML;
                },
                ''
            ),
            $page
        ));
    }

    /**
     * @throws CurlException
     */
    private function getCurl(string $url): Curl
    {
        return Curl::withHeaderAuth(
            sprintf(
                '%s/%s?company_id=%d&inputFormat=xml&outputFormat=json',
                $this->wfirmaApiUrl,
                $url,
                $this->credentials->getCompanyId()
            ),
            $this->credentials->asHeaders()
        );
    }

    /**
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
     * @throws TotalRequestsLimitExceededException
     * @throws TotalExecutionTimeLimitExceededException
     * @throws ErrorResponseException
     */
    private function handleResponse(array $result, string $url, string $data = ''): array
    {
        switch ($result['status']['code']) {
            case 'OK':
                return $result;
            case 'NOT FOUND':
                throw new NotFoundException($url, $result, $data);
            case 'AUTH':
            case 'AUTH FAILED LIMIT WAIT 5 MINUTES':
                throw new AuthorizationException($url, $result, $data);
            case 'OUT OF SERVICE':
                throw new OutOfServiceException($url, $result, $data);
            case 'TOTAL EXECUTION TIME LIMIT EXCEEDED':
                throw new TotalExecutionTimeLimitExceededException($url, $result, $data);
            case 'TOTAL REQUESTS LIMIT EXCEEDED':
                throw new TotalRequestsLimitExceededException($url, $result, $data);
            case 'ERROR':
                throw new ErrorResponseException($url, $result, $data);
            case 'FATAL':
            default:
                throw new FatalException($url, $result, $data);
        }
    }

    /**
     * @throws NotFoundException
     */
    private function handleFileResponse(string $result, string $url, string $data = ''): string
    {
        $jsonResult = json_decode($result, true);

        if (null !== $jsonResult && 'ERROR' === $jsonResult['status']['code']) {
            throw new NotFoundException($url, [$result], $data);
        }

        return $result;
    }

    private function logWFirmaRequest(string $url, string $data = ''): void
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->logRequest($url, $data);
    }
}
