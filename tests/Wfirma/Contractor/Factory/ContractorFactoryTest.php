<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor\Factory;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use PHPUnit\Framework\TestCase;

final class ContractorFactoryTest extends TestCase
{
    public function testCreatingPerson(): void
    {
        $factory = new ContractorFactory();
        $contractor = $factory->getContractor([
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
        $factory = new ContractorFactory();
        $contractor = $factory->getContractor([
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
