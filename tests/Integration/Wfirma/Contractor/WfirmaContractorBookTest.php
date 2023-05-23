<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Wfirma\Contractor;

use Generator;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\InvalidVatIdException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax\MemoryIdentifierFactory;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaApiUrl;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaConditionTransformer;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\WfirmaContractorBook;
use Throwable;

final class WfirmaContractorBookTest extends IntegrationTestCase
{
    private ContractorBook $book;

    public function setUp(): void
    {
        $this->book = new WfirmaContractorBook(
            new WfirmaClient(
                new WfirmaApiUrl((string) getenv('WFIRMA_API_URL')),
                new WfirmaCredentials(
                    (string) getenv('WFIRMA_API_ACCESS_KEY'),
                    (string) getenv('WFIRMA_API_SECRET_KEY'),
                    (string) getenv('WFIRMA_API_APP_KEY'),
                    (int) getenv('WFIRMA_API_COMPANY_ID')
                ),
                new WfirmaConditionTransformer()
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

        $this->assertNotEmpty($contractor->getIdentifier()->toString());
        $this->assertEquals($person->getEmail(), $contractor->getEmail());

        $contractor = $this->book->find($contractor->getIdentifier());

        $this->assertInstanceOf(Person::class, $contractor);
        $this->assertEquals($person->getEmail(), $contractor->getEmail());

        $contractor->changeEmail($newEmail = new ContractorEmail('contractor-person@example.com'));
        $contractor = $this->book->update($contractor);

        $this->assertEquals($newEmail, $contractor->getEmail());

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
        $this->assertEquals($company->getEmail(), $contractor->getEmail());

        $contractor->changeEmail($newEmail = new ContractorEmail('contractor-company@example.com'));
        $contractor = $this->book->update($contractor);

        $this->assertEquals($newEmail, $contractor->getEmail());

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

    /**
     * @dataProvider invalidCompanies
     *
     * @param Contractor $company
     * @param class-string<Throwable> $exceptionClass
     */
    public function testErrorResponseThrowsException(Contractor $company, string $exceptionClass): void
    {
        $this->expectException($exceptionClass);
        $this->book->create($company);
    }

    /**
     * @internal use only in testErrorResponseThrowsException function
     */
    public function invalidCompanies(): Generator
    {
        $factory = new ContractorFactory(new MemoryIdentifierFactory());
        $data = json_decode(
            (string) file_get_contents(__DIR__ . '/Resources/companies_invalid.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($data['companies'] as $company) {
            yield [$factory->getContractor($company), InvalidVatIdException::class];
        }
    }
}
