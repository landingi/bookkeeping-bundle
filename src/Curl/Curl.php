<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Curl;

use ErrorException;
use RuntimeException;

final class Curl
{
    private $curl;

    /**
     * @throws ErrorException
     */
    public static function withBasicAuth(string $url, string $credentials): self
    {
        $curl = new self($url);
        $curl->setOpt(CURLOPT_USERPWD, $credentials);

        return $curl;
    }

    /**
     * @throws ErrorException
     */
    public function __construct(string $url)
    {
        if (!extension_loaded('curl')) {
            throw new ErrorException('cURL library is not loaded');
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

    private function exec(): string
    {
        $response = curl_exec($this->curl);

        if (false === $response) {
            throw new RuntimeException('cURL failed');
        }

        return $response;
    }
}
