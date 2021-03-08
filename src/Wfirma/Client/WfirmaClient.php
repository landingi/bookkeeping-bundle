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

    public function getVatId(string $countryId, int $vatRate): int
    {
        $country = $this->requestPOST('declaration_countries/find', <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <declaration_countries>
        <parameters>
            <conditions>
                <condition>
                    <field>code</field>    
                    <operator>eq</operator>
                    <value>{$countryId}</value>
                </condition>
            </conditions>
        </parameters>
    </declaration_countries>
</api>
XML);
        $vatCode = $this->requestPOST(
            'vat_codes/find',
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <vat_codes>
        <parameters>
            <conditions>
                <condition>
                    <field>declaration_country_id</field>    
                    <operator>eq</operator>
                    <value>{$country['declaration_countries'][0]['declaration_country']['id']}</value>
                </condition>
                <condition>
                    <field>rate</field>    
                    <operator>eq</operator>
                    <value>{$vatRate}</value>
                </condition>
            </conditions>
        </parameters>
    </vat_codes>
</api>
XML
        );

        return $vatCode['vat_codes'][0]['vat_code']['id'];
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
