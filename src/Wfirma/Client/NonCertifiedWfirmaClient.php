<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

use JsonException;
use Landingi\BookkeepingBundle\Curl\Curl;
use Landingi\BookkeepingBundle\Curl\CurlException;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\NonCertifiedWfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\AuthorizationException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\ErrorResponseException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\FatalException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\NotFoundException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\OutOfServiceException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalExecutionTimeLimitExceededException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalRequestsLimitExceededException;
use Landingi\BookkeepingBundle\Wfirma\WFirmaLogger;
use function json_decode;
use function sprintf;

final class NonCertifiedWfirmaClient
{
    private WfirmaApiUrl $wfirmaApiUrl;
    private NonCertifiedWfirmaCredentials $credentials;
    private ?WFirmaLogger $logger;

    public function __construct(
        WfirmaApiUrl $wfirmaApiUrl,
        NonCertifiedWfirmaCredentials $credentials,
        ?WFirmaLogger $logger = null
    ) {
        $this->wfirmaApiUrl = $wfirmaApiUrl;
        $this->credentials = $credentials;
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
    public function requestPOST(string $url, string $data): array
    {
        $this->logWFirmaRequest($url, $data);

        return $this->handleResponse(json_decode($this->getCurl($url)->requestPOST($data), true, 512, JSON_THROW_ON_ERROR), $url);
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

    private function logWFirmaRequest(string $url, string $data = ''): void
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->logRequest($url, $data);
    }
}
