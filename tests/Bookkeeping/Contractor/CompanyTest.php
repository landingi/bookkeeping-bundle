<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use PHPUnit\Framework\TestCase;

final class CompanyTest extends TestCase
{
    public function testItIsEuropeanUnionCitizen(): void
    {
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('PL')
            ),
            new ValueAddedTaxIdentifier('id')
        );
        self::assertTrue($company->isEuropeanUnionCitizen());
    }

    public function testItPrints(): void
    {
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('PL')
            ),
            new ValueAddedTaxIdentifier('id')
        );

        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
</api>
XML,
            $company->print(WfirmaMedia::api())->toString()
        );
    }
}
