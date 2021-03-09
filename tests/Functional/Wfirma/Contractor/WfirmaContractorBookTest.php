<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Functional\Wfirma\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\WfirmaContractorBook;
use PHPUnit\Framework\TestCase;

final class WfirmaContractorBookTest extends TestCase
{
    private ContractorBook $book;

    public function setUp(): void
    {
        $this->book = new WfirmaContractorBook(
            new WfirmaClient(new WfirmaCredentials('', '', 0)),
            new ContractorFactory()
        );
    }
    public function testPersonWorkflow(): void
    {
        $contractor = $this->book->create(
            new Person(
                new ContractorIdentifier('123'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('111-111'),
                    new City('test'),
                    new Country('PL')
                )
            )
        );

        self::assertNotEmpty($contractor->getIdentifier()->toString());

        $contractor = $this->book->find($contractor->getIdentifier());

        self::assertInstanceOf(Person::class, $contractor);

        $this->book->delete($contractor->getIdentifier());
    }

    public function testCompanyWorkflow(): void
    {
        $contractor = $this->book->create(
            new Company(
                new ContractorIdentifier('12345'),
                new ContractorName('test foo'),
                new ContractorEmail('test@landingi.com'),
                new ContractorAddress(
                    new Street('test 123'),
                    new PostalCode('11-111'),
                    new City('test'),
                    new Country('PL')
                ),
                new Company\ValueAddedTaxIdentifier('6482791634')
            )
        );

        self::assertNotEmpty($contractor->getIdentifier()->toString());

        $contractor = $this->book->find($contractor->getIdentifier());

        self::assertInstanceOf(Company::class, $contractor);

        $this->book->delete($contractor->getIdentifier());
    }
}
