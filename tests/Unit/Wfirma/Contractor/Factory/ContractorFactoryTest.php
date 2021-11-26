<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Contractor\Factory;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax\MemoryIdentifierFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use PHPUnit\Framework\TestCase;

final class ContractorFactoryTest extends TestCase
{
    private ContractorFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new ContractorFactory(
            new MemoryIdentifierFactory()
        );
    }

    public function testCreatingPerson(): void
    {
        $contractor = $this->factory->getContractor([
            'id' => '123',
            'name' => 'test',
            'email' => 'test@test.com',
            'street' => 'Test Avenue',
            'zip' => '11-111',
            'city' => 'Test',
            'country' => 'PL',
        ]);
        self::assertInstanceOf(Person::class, $contractor);
    }

    public function testCreatingCompany(): void
    {
        $contractor = $this->factory->getContractor([
            'id' => '123',
            'name' => 'test',
            'email' => 'test@test.com',
            'nip' => '1234567890',
            'street' => 'Test Avenue',
            'zip' => '11-111',
            'city' => 'Test',
            'country' => 'PL',
        ]);
        self::assertInstanceOf(Company::class, $contractor);
    }
}
