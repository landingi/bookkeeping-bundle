<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClientException;

final class TotalExecutionTimeLimitExceeded extends WfirmaClientException
{
    public function __construct(string $url, array $result, string $request)
    {
        parent::__construct($url, $result, $request, 'Total execution time limit exceeded.');
    }
}
