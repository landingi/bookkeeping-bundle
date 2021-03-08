<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\WFirmaException;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;

final class WfirmaContractorBook implements ContractorBook
{
    private const CONTRACTOR_API_URL = 'http://api2.wfirma.pl/contractors/%s';

    private WfirmaClient $client;
    private ContractorFactory $contractorFactory;

    public function __construct(WfirmaClient $client, ContractorFactory $contractorFactory)
    {
        $this->client = $client;
        $this->contractorFactory = $contractorFactory;
    }

    /**
     * @throws WFirmaException
     * @throws Contractor\ContractorException
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
     * @throws WFirmaException
     * @throws Contractor\ContractorException
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
                    $contractor->print(new WfirmaMedia(new \SimpleXMLElement('')))->toString()
                )
            )
        );
    }

    public function delete(ContractorIdentifier $identifier): void
    {
        $this->client->requestDELETE(sprintf('/contractors/delete/%s', $identifier->toString()));
    }

    private function getContractorResult(array $response): array
    {
        if (false === isset($response['contractors'][0]['contractor'])) {
            throw new WFirmaException('Invalid response structure!');
        }

        return $response['contractors'][0]['contractor'];
    }
}
