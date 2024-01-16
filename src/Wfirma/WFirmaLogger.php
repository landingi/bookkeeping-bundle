<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

interface WFirmaLogger
{
    public function logRequest(string $url, string $payload): void;
}