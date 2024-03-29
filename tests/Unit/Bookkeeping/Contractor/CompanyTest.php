<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use PHPUnit\Framework\TestCase;

final class CompanyTest extends TestCase
{
    public function testItIsPolishCompany(): void
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
            new ValueAddedTax\SimpleIdentifier('id')
        );
        $this->assertTrue($company->isPolish());
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('GB')
            ),
            new ValueAddedTax\SimpleIdentifier('id')
        );
        $this->assertFalse($company->isPolish());
    }

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
            new ValueAddedTax\SimpleIdentifier('id')
        );
        $this->assertFalse($company->isEuropeanUnionCitizen());
    }

    public function testItIsEuropeanUnionCompany(): void
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
            new ValueAddedTax\SimpleIdentifier('id')
        );
        $this->assertTrue($company->isEuropeanUnionCompany());
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('AG')
            ),
            new ValueAddedTax\SimpleIdentifier('id')
        );
        $this->assertFalse($company->isEuropeanUnionCompany());
    }

    public function testItPrintsPolishCompany(): void
    {
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('street'),
                new PostalCode('postal'),
                new City('city'),
                new Country('PL')
            ),
            new ValueAddedTax\SimpleIdentifier('333444555')
        );

        $this->assertXmlStringEqualsXmlString(
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
            <tax_id_type>nip</tax_id_type>
            <nip>333444555</nip>
        </contractor>
    </contractors>
</api>
XML,
            $company->print(WfirmaMedia::api())->toString()
        );
    }

    public function testItPrintsEuropeanUnionCompany(): void
    {
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('street'),
                new PostalCode('postal'),
                new City('city'),
                new Country('DE')
            ),
            new ValueAddedTax\SimpleIdentifier('333444555')
        );

        $this->assertXmlStringEqualsXmlString(
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
            <country>DE</country>
            <email>name@foo.bar</email>
            <tax_id_type>vat</tax_id_type>
            <nip>333444555</nip>
        </contractor>
    </contractors>
</api>
XML,
            $company->print(WfirmaMedia::api())->toString()
        );
    }

    public function testItPrintsGreekCompany(): void
    {
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('street'),
                new PostalCode('postal'),
                new City('city'),
                new Country('EL')
            ),
            new ValueAddedTax\SimpleIdentifier('333444555')
        );

        $this->assertXmlStringEqualsXmlString(
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
            <country>GR</country>
            <email>name@foo.bar</email>
            <tax_id_type>vat</tax_id_type>
            <nip>333444555</nip>
        </contractor>
    </contractors>
</api>
XML,
            $company->print(WfirmaMedia::api())->toString()
        );
    }

    public function testItPrintsCompany(): void
    {
        $company = new Company(
            new ContractorIdentifier('id'),
            new ContractorName('name'),
            new ContractorEmail('name@foo.bar'),
            new ContractorAddress(
                new Street('street'),
                new PostalCode('postal'),
                new City('city'),
                new Country('UY') //Uruguay
            ),
            new ValueAddedTax\SimpleIdentifier('333444555')
        );

        $this->assertXmlStringEqualsXmlString(
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
            <country>UY</country>
            <email>name@foo.bar</email>
            <tax_id_type>custom</tax_id_type>
            <nip>333444555</nip>
        </contractor>
    </contractors>
</api>
XML,
            $company->print(WfirmaMedia::api())->toString()
        );
    }
}
