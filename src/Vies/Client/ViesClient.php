<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Vies\Client;

use Landingi\BookkeepingBundle\Curl\Curl;
use Landingi\BookkeepingBundle\Curl\CurlException;
use function json_decode;
use function sprintf;

final class ViesClient
{
    private const VIES_PROTOCOL = 'https';
    private const VIES_DOMAIN = 'ec.europa.eu';
    private const VIES_PATH = 'taxation_customs/vies/rest-api/ms/%s/vat/%s';

    public function validateVat(string $countryCode, string $identifier): array
    {
        $curl = $this->getCurl($countryCode, $identifier);
        $response = json_decode($curl->requestGET(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($response['isValid'])) {
            throw new \RuntimeException('Invalid response from VIES REST API');
        }

        return $response;
    }

    /**
     * @throws CurlException
     */
    private function getCurl(string $countryCode, string $identifier): Curl
    {
        $path = sprintf(
            self::VIES_PATH,
            $countryCode,
            $identifier
        );

        return Curl::withHeaderAuth(
            sprintf(
                '%s://%s/%s',
                self::VIES_PROTOCOL,
                self::VIES_DOMAIN,
                $path,
            ),
            []
        );
    }
}
