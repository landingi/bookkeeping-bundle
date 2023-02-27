<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\Exception\TotalExecutionTimeLimitExceededException;
use PHPUnit\Framework\TestCase;

class TotalExecutionTimeLimitExceededExceptionTest extends TestCase
{
    public function testConstructorSetsExpectedValues()
    {
        $exception = new TotalExecutionTimeLimitExceededException(
            $url = 'https://test.example.com',
            $result = ['foo' => 'bar'],
            $requestData = 'foobarbaz'
        );
        self::assertEquals($url, $exception->getUrl());
        self::assertEquals($result, $exception->getResult());
        self::assertEquals($requestData, $exception->getRequest());
        self::assertEquals('Total execution time limit exceeded.', $exception->getMessage());
    }
}
