<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Wfirma\Contractor;

use Generator;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax\MemoryIdentifierFactory;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\WfirmaContractorBook;

final class WfirmaContractorBookTest extends IntegrationTestCase
{
    private ContractorBook $book;

    public function setUp(): void
    {
        $this->book = new WfirmaContractorBook(
            new WfirmaClient(
                new WfirmaCredentials(
                    (string) getenv('WFIRMA_API_LOGIN'),
                    (string) getenv('WFIRMA_API_PASSWORD'),
                    (int) getenv('WFIRMA_API_COMPANY')
                )
            ),
            new ContractorFactory(
                new MemoryIdentifierFactory()
            )
        );
    }

    /**
     * @dataProvider people
     */
    public function testPersonWorkflow(Contractor $person): void
    {
        $contractor = $this->book->create($person);

        self::assertNotEmpty($contractor->getIdentifier()->toString());

        $contractor = $this->book->find($contractor->getIdentifier());

        self::assertInstanceOf(Person::class, $contractor);

        $this->book->delete($contractor->getIdentifier());
    }

    /**
     * @internal use only in testPersonWorkflow function
     */
    public function people(): Generator
    {
        $factory = new ContractorFactory(new MemoryIdentifierFactory());
        $data = json_decode((string) file_get_contents(__DIR__ . '/Resources/people.json'), true, 512, JSON_THROW_ON_ERROR);

        foreach ($data['people'] as $company) {
            yield [$factory->getContractor($company)];
        }
    }

    /**
     * @dataProvider companies
     */
    public function testCompanyWorkflow(Contractor $company): void
    {
        $contractor = $this->book->create($company);

        self::assertNotEmpty($contractor->getIdentifier()->toString());

        $contractor = $this->book->find($contractor->getIdentifier());

        self::assertInstanceOf(Company::class, $contractor);

        $this->book->delete($contractor->getIdentifier());
    }

    /**
     * @internal use only in testCompanyWorkflow function
     */
    public function companies(): Generator
    {
        $factory = new ContractorFactory(new MemoryIdentifierFactory());
        $data = json_decode((string) file_get_contents(__DIR__ . '/Resources/companies.json'), true, 512, JSON_THROW_ON_ERROR);

        foreach ($data['companies'] as $company) {
            yield [$factory->getContractor($company)];
        }
    }
}
