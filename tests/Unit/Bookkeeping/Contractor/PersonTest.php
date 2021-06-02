<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{
    public function testItIsEuropeanUnionCitizen(): void
    {
        $person = new Person(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('PL')
            )
        );
        self::assertTrue($person->isEuropeanUnionCitizen());
        $person = new Person(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('US')
            )
        );
        self::assertFalse($person->isEuropeanUnionCitizen());
    }

    public function testItIsEuropeanUnionCompany(): void
    {
        $person = new Person(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('PL')
            )
        );
        self::assertFalse($person->isEuropeanUnionCompany());
    }

    public function testItPrints(): void
    {
        $person = new Person(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('street'),
                new PostalCode('postal'),
                new City('city'),
                new Country('PL')
            )
        );

        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <contractors>
        <contractor>
            <name>name</name>
            <altname>name</altname>
            <street>street</street>
            <zip>postal</zip>
            <city>city</city>
            <country>PL</country>
            <email>name@foo.bar</email>
        </contractor>
    </contractors>
</api>
XML,
            $person->print(WfirmaMedia::api())->toString()
        );
    }
}
