<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

interface Request
{
    public function getContent(): string;
}
