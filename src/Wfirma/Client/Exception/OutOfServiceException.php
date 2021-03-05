<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\WFirmaClientException;

final class OutOfServiceException extends WFirmaClientException
{
    public function __construct(string $url, array $result, string $request)
    {
        parent::__construct($url, $result, $request, 'External system is unavailable');
    }
}
