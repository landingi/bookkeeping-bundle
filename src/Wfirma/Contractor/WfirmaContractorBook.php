<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor;

use JsonException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
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
     * @throws WfirmaException
     * @throws ContractorException
     * @throws JsonException
     */
    public function create(Contractor $contractor): Contractor
    {
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

    public function update(Contractor $contractor): Contractor
    {
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
}
