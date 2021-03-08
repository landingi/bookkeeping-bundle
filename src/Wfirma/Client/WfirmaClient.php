<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

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
     */
    public function requestGET(string $url): array
    {
        return $this->handleResponse(json_decode($this->getCurl($url)->requestGET(), true), $url);
    }

    /**
     * @throws WfirmaClientException
     */
    public function requestPOST(string $url, string $data): array
    {
        return $this->handleResponse(json_decode($this->getCurl($url)->requestPOST($data), true), $url);
    }

    /**
     * @throws WfirmaClientException
     */
    public function requestDELETE(string $url): array
    {
        return $this->handleResponse(json_decode($this->getCurl($url)->requestDELETE(), true), $url);
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
     *
     * @phpstan-ignore-next-line
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
