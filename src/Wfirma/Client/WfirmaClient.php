<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

use JsonException;
use Landingi\BookkeepingBundle\Curl\Curl;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\AuthorizationException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\FatalException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\NotFoundException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\OutOfServiceException;
use Landingi\BookkeepingBundle\Wfirma\Client\Request\Invoice\Download;
use function is_string;
use function sprintf;

final class WfirmaClient
{
    private WfirmaCredentials $credentials;
    private const API = 'https://api2.wfirma.pl';

    public function __construct(WfirmaCredentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @throws WfirmaClientException
     * @throws JsonException
     */
    public function requestGET(string $url): array
    {
        return $this->handleResponse(json_decode($this->getCurl($url)->requestGET(), true, 512, JSON_THROW_ON_ERROR), $url);
    }

    /**
     * @throws WfirmaClientException
     * @throws JsonException
     */
    public function requestPOST(string $url, string $data): array
    {
        return $this->handleResponse(json_decode($this->getCurl($url)->requestPOST($data), true, 512, JSON_THROW_ON_ERROR), $url);
    }

    /**
     * @throws WfirmaClientException
     * @throws JsonException
     */
    public function requestDELETE(string $url): array
    {
        return $this->handleResponse(json_decode($this->getCurl($url)->requestDELETE(), true, 512, JSON_THROW_ON_ERROR), $url);
    }

    /**
     * @throws \JsonException
     * @throws \Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClientException
     */
    public function getVatId(string $countryCode, int $vatRate): int
    {
        /**
         * WFirma API does not recognize PL VAT tax, but the VAT id is 0
         */
        if ($countryCode === 'PL') {
            return 0;
        }

        $country = $this->requestPOST(
            'declaration_countries/find',
            (string) (new Request\DeclarationCountries\Find($countryCode))
        );

        if (empty($country['declaration_countries'])) {
            throw new WfirmaClientException(
                'declaration_countries/find',
                $country,
                'declaration_countries',
                'Declaration Country not found'
            );
        }

        $vatCode = $this->requestPOST(
            'vat_codes/find',
            (string) (new Request\VatCodes\Find(
                (int) $country['declaration_countries'][0]['declaration_country']['id'],
                $vatRate
            ))
        );

        if (empty($vatCode['vat_codes'])) {
            throw new WfirmaClientException(
                'vat_codes/find',
                $vatCode,
                'vat_codes',
                'Vat Code Not Found'
            );
        }

        return (int) $vatCode['vat_codes'][0]['vat_code']['id'];
    }

    /**
     * @throws WfirmaClientException
     */
    public function requestInvoiceDownload(string $url): string
    {
        $result = $this->getCurl($url)->requestPOST((string) new Download());

        if (!is_string($result)) {
            throw new WfirmaClientException($url, [$result], 'invoice_download', 'Invalid response');
        }

        return $result;
    }

    private function getCurl(string $url): Curl
    {
        return Curl::withBasicAuth(
            sprintf(
                '%s/%s?company_id=%d&inputFormat=xml&outputFormat=json',
                self::API,
                $url,
                $this->credentials->getCompanyId()
            ),
            $this->credentials->toString()
        );
    }

    /**
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
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
            case 'FATAL':
            default:
                throw new FatalException($url, $result, $data);
        }
    }
}
