<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

final class WfirmaApiUrl
{
    private string $wfirmaUrl;

    public function __construct(string $wfirmaUrl)
    {
        $this->wfirmaUrl = $wfirmaUrl;
    }

    public function __toString(): string
    {
        return $this->wfirmaUrl;
    }
}
