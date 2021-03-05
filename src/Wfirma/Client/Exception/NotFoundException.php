<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClientException;

final class NotFoundException extends WfirmaClientException
{
    public function __construct(string $url, array $result, string $request)
    {
        parent::__construct($url, $result, $request, 'External system returns 404 code');
    }
}
