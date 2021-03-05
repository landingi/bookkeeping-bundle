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

    public function requestGET(string $url): string
    {
        return '';
    }

    public function requestPOST(string $url, string $data): string
    {
        return '';
    }

    public function requestDELETE(string $url): string
    {
        $curl = Curl::withBasicAuth(
            sprintf(
                '%s/%s?company_id=%d&inputFormat=xml&outputFormat=json',
                self::API,
                $url,
                $this->credentials->getCompanyId()
            ),
            $this->credentials->toString()
        );

        $curl->requestDELETE();

        return '';
    }

    /**
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
     */
    private function handleResponse(array $result, string $url, string $data): array
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
