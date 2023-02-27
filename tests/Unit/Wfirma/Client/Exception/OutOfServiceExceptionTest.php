<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\Exception\OutOfServiceException;
use PHPUnit\Framework\TestCase;

class OutOfServiceExceptionTest extends TestCase
{
    public function testConstructorSetsExpectedValues()
    {
        $exception = new OutOfServiceException(
            $url = 'https://test.example.com',
            $result = ['foo' => 'bar'],
            $requestData = 'foobarbaz'
        );
        self::assertEquals($url, $exception->getUrl());
        self::assertEquals($result, $exception->getResult());
        self::assertEquals($requestData, $exception->getRequest());
        self::assertEquals('External system is unavailable', $exception->getMessage());
    }
}
