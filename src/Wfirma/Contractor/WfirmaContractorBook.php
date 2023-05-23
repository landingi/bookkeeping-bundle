<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor;

use JsonException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\InvalidEmailAddressException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\InvalidVatIdException;
use Landingi\BookkeepingBundle\Curl\CurlException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\AuthorizationException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\ErrorResponseException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\FatalException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\NotFoundException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\OutOfServiceException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalExecutionTimeLimitExceededException;
use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalRequestsLimitExceededException;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClientException;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\WfirmaException;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use function sprintf;

final class WfirmaContractorBook implements ContractorBook
{
    private const CONTRACTOR_API_URL = '/contractors/%s';

    private WfirmaClient $client;
    private ContractorFactory $contractorFactory;

    public function __construct(WfirmaClient $client, ContractorFactory $contractorFactory)
    {
        $this->client = $client;
        $this->contractorFactory = $contractorFactory;
    }

    /**
     * @throws WfirmaException
     * @throws ContractorException
     * @throws JsonException
     */
    public function find(ContractorIdentifier $identifier): Contractor
    {
        return $this->contractorFactory->getContractor(
            $this->getContractorResult(
                $this->client->requestGET(
                    sprintf(
                        self::CONTRACTOR_API_URL,
                        sprintf(
                            '%s%s',
                            'get/',
                            $identifier->toString()
                        )
                    )
                )
            )
        );
    }

    /**
     * @throws ContractorException
     * @throws InvalidEmailAddressException
     * @throws ErrorResponseException
     * @throws JsonException
     * @throws WfirmaException
     * @throws CurlException
     * @throws AuthorizationException
     * @throws FatalException
     * @throws NotFoundException
     * @throws OutOfServiceException
     * @throws TotalExecutionTimeLimitExceededException
     * @throws TotalRequestsLimitExceededException
     *
     * @return Contractor
     */
    public function create(Contractor $contractor): Contractor
    {
        try {
            return $this->contractorFactory->getContractor(
                $this->getContractorResult(
                    $this->client->requestPOST(
                        sprintf(
                            self::CONTRACTOR_API_URL,
                            sprintf('%s', 'add')
                        ),
                        $contractor->print(WfirmaMedia::api())->toString()
                    )
                )
            );
        } catch (ErrorResponseException $e) {
            try {
                $contractor = $this->getContractorResult($e->getResult());
            } catch (WfirmaException $invalidResponseStructureException) {
                throw $e;
            }

            $this->tryToThrowSpecificExceptionFromResponse($contractor);
            throw $e;
        }
    }

    /**
     * @throws JsonException
     * @throws WfirmaClientException
     */
    public function delete(ContractorIdentifier $identifier): void
    {
        $this->client->requestDELETE(
            sprintf(
                self::CONTRACTOR_API_URL,
                sprintf('%s/%s', '/delete', $identifier->toString())
            )
        );
    }

    /**
     * @throws AuthorizationException
     * @throws ContractorException
     * @throws CurlException
     * @throws ErrorResponseException
     * @throws FatalException
     * @throws InvalidEmailAddressException
     * @throws InvalidVatIdException
     * @throws JsonException
     * @throws NotFoundException
     * @throws OutOfServiceException
     * @throws TotalExecutionTimeLimitExceededException
     * @throws TotalRequestsLimitExceededException
     * @throws WfirmaException
     *
     * @return Contractor
     */
    public function update(Contractor $contractor): Contractor
    {
        try {
            return $this->contractorFactory->getContractor(
                $this->getContractorResult(
                    $this->client->requestPOST(
                        sprintf(
                            self::CONTRACTOR_API_URL,
                            "edit/{$contractor->getIdentifier()}"
                        ),
                        $contractor->print(WfirmaMedia::api())->toString()
                    )
                )
            );
        } catch (ErrorResponseException $e) {
            try {
                $contractor = $this->getContractorResult($e->getResult());
            } catch (WfirmaException $invalidResponseStructureException) {
                throw $e;
            }

            $this->tryToThrowSpecificExceptionFromResponse($contractor);
            throw $e;
        }
    }

    /**
     * @throws WfirmaException
     */
    private function getContractorResult(array $response): array
    {
        if (false === isset($response['contractors'][0]['contractor'])) {
            throw new WfirmaException('Invalid response structure!');
        }

        return $response['contractors'][0]['contractor'];
    }

    private function tryToThrowSpecificExceptionFromResponse(array $contractor): void
    {
        $error = $contractor['errors'][0]['error'] ?? [];

        if (isset($error['field'])) {
            switch ($error['field']) {
                case 'nip':
                    throw new InvalidVatIdException($error['message'] ?: 'Invalid Vat Id provided');
                default:
                    // Do nothing
            }
        }
    }
}
