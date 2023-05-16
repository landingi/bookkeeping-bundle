<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Curl;

use function curl_error;
use function curl_exec;
use function curl_init;
use function curl_setopt;
use function extension_loaded;
use function sprintf;

final class Curl
{
    private $curl;

    /**
     * @throws CurlException
     */
    public static function withHeaderAuth(string $url, array $headers): self
    {
        $curl = new self($url);
        $curl->setOpt(CURLOPT_HTTPHEADER, array_map(
            fn ($key, $value) => "$key: $value",
            array_keys($headers),
            array_values($headers)
        ));

        return $curl;
    }

    /**
     * @throws CurlException
     */
    public function __construct(string $url)
    {
        if (!extension_loaded('curl')) {
            throw new CurlException('cURL library is not loaded');
        }

        $curl = curl_init();

        if (false === $curl) {
            throw new CurlException('Could not initialize cURL');
        }

        $this->curl = $curl;
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * @throws \Landingi\BookkeepingBundle\Curl\CurlException
     */
    public function requestGET(): string
    {
        return $this->exec();
    }

    /**
     * @throws \Landingi\BookkeepingBundle\Curl\CurlException
     */
    public function requestPOST(string $data): string
    {
        $this->setOpt(CURLOPT_POST, 1);
        $this->setOpt(CURLOPT_POSTFIELDS, $data);

        return $this->exec();
    }

    /**
     * @throws \Landingi\BookkeepingBundle\Curl\CurlException
     */
    public function requestDELETE(): string
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->exec();
    }

    /**
     * @throws \Landingi\BookkeepingBundle\Curl\CurlException
     */
    private function setOpt($option, $value): void
    {
        if (false === curl_setopt($this->curl, $option, $value)) {
            throw new CurlException(
                sprintf(
                    'Could not set curl option (%s) with value (%s)',
                    (string) $option,
                    (string) $value
                )
            );
        }
    }

    /**
     * @throws CurlException
     */
    private function exec(): string
    {
        $response = curl_exec($this->curl);
        var_dump($response);
        if (false === $response) {
            throw new CurlException('cURL error: ' . curl_error($this->curl));
        }

        return (string) $response;
    }
}
