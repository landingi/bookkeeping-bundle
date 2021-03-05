<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Curl;

final class Curl
{
    private $curl;

    public static function withBasicAuth(string $url, string $credentials)
    {
        $curl = new self($url);
        $curl->setOpt(CURLOPT_USERPWD, $credentials);

        return $curl;
    }

    public function __construct(string $url)
    {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL library is not loaded');
        }

        $this->curl = curl_init();
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    public function requestGET()
    {
        return $this->exec();
    }

    public function requestPOST(string $data)
    {
        $this->setOpt(CURLOPT_POST, 1);
        $this->setOpt(CURLOPT_POSTFIELDS, $data);

        return $this->exec();
    }

    private function setOpt($option, $value): bool
    {
        return curl_setopt($this->curl, $option, $value);
    }

    private function exec()
    {
        return curl_exec($this->curl);
    }
}
