<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Curl;

use function curl_error;
use function curl_exec;
use function curl_init;
use function curl_setopt;

final class Curl
{
    private $curl;

    /**
     * @throws CurlException
     */
    public static function withBasicAuth(string $url, string $credentials): self
    {
        $curl = new self($url);
        $curl->setOpt(CURLOPT_USERPWD, $credentials);

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

        $this->curl = curl_init();
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    public function requestGET(): string
    {
        return $this->exec();
    }

    public function requestPOST(string $data): string
    {
        $this->setOpt(CURLOPT_POST, 1);
        $this->setOpt(CURLOPT_POSTFIELDS, $data);

        return $this->exec();
    }

    public function requestDELETE(): string
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->exec();
    }

    private function setOpt($option, $value): bool
    {
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * @throws CurlException
     */
    private function exec(): string
    {
        $response = curl_exec($this->curl);

        if (false === $response) {
            throw new CurlException('cURL error: ' . curl_error($this->curl));
        }

        return $response;
    }
}
