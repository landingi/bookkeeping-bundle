<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalRequestsLimitExceededException;
use PHPUnit\Framework\TestCase;

class TotalRequestLimitExceededExceptionTest extends TestCase
{
    public function testConstructorSetsExpectedValues(): void
    {
        $exception = new TotalRequestsLimitExceededException(
            $url = 'https://test.example.com',
            $result = ['foo' => 'bar'],
            $requestData = 'foobarbaz'
        );
        self::assertEquals($url, $exception->getUrl());
        self::assertEquals($result, $exception->getResult());
        self::assertEquals($requestData, $exception->getRequest());
        self::assertEquals('Total requests limit exceeded.', $exception->getMessage());
    }
}
