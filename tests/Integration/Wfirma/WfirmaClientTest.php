<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Wfirma;

use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaApiUrl;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClientException;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaConditionTransformer;

final class WfirmaClientTest extends IntegrationTestCase
{
    private WfirmaClient $client;

    public function setUp(): void
    {
        $this->client = new WfirmaClient(
            new WfirmaApiUrl((string) getenv('WFIRMA_API_URL')),
            new WfirmaCredentials(
                (string) getenv('WFIRMA_API_ACCESS_KEY'),
                (string) getenv('WFIRMA_API_SECRET_KEY'),
                (string) getenv('WFIRMA_API_APP_KEY'),
                (int) getenv('WFIRMA_API_COMPANY_ID')
            ),
            new WfirmaConditionTransformer()
        );
    }

    public function testItDoesNotFindCountry(): void
    {
        $this->expectException(WfirmaClientException::class);
        $this->client->getVatId('ZZ', 0);
    }

    public function testItDoesNotFindValueAddedTaxRate(): void
    {
        $this->expectException(WfirmaClientException::class);
        $this->client->getVatId('DE', 999);
    }

    /**
     * @dataProvider getCountriesValueAddedTaxRate
     */
    public function testItGetsVatIdentifier(string $country, float $rate): void
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
        yield ['FI', 25.5];
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
        yield ['SK', 23];
        yield ['SI', 22];
        yield ['SE', 25];
    }
}
