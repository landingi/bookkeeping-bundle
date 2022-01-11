<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

use Landingi\BookkeepingBundle\Wfirma\WfirmaException;

class WfirmaClientException extends WfirmaException
{
    protected string $url;
    protected array $result;
    protected string $request;

    public function __construct(string $url, array $result, string $request, string $message)
    {
        $this->request = $request;
        $this->result = $result;
        $this->url = $url;

        parent::__construct($message . '' . json_encode($result, JSON_THROW_ON_ERROR));
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function getRequest(): string
    {
        return $this->request;
    }
}
