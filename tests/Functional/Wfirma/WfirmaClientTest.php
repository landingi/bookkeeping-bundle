<?php
declare(strict_types=1);

namespace Functional\Wfirma;

use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use PHPUnit\Framework\TestCase;

final class WfirmaClientTest extends TestCase
{
    private WfirmaClient $client;

    public function setUp(): void
    {
        $this->client = new WfirmaClient(
            new WfirmaCredentials(
                (string) getenv('WFIRMA_API_LOGIN'),
                (string) getenv('WFIRMA_API_PASSWORD'),
                (int) getenv('WFIRMA_API_COMPANY')
            )
        );
    }

    /**
     * @dataProvider getCountriesValueAddedTaxRate
     */
    public function testItGetsVatIdentifier(string $country, int $rate): void
    {
        $this->client->getVatId($country, $rate);
        $this->expectNotToPerformAssertions();
    }

    public function getCountriesValueAddedTaxRate(): \Generator
    {
        yield ['AT', 20];
        yield ['BE', 21];
        yield ['BG', 20];
        yield ['CY', 19];
        yield ['CZ', 21];
        yield ['DE', 19];
        yield ['DK', 25];
        yield ['EE', 20];
        yield ['ES', 21];
        yield ['FI', 24];
        yield ['FR', 20];
        yield ['GR', 24];
        yield ['HR', 25];
        yield ['HU', 27];
        yield ['IE', 23];
        yield ['IT', 22];
        yield ['LT', 21];
        yield ['LV', 21];
        yield ['LU', 17];
        yield ['MT', 18];
        yield ['NL', 21];
        yield ['PL', 23];
        yield ['PT', 23];
        yield ['RO', 19];
        yield ['SK', 20];
        yield ['SI', 22];
        yield ['SE', 25];
    }
}
