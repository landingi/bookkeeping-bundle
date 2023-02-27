<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\Exception\AuthorizationException;
use PHPUnit\Framework\TestCase;

class AuthorizationExceptionTest extends TestCase
{
    public function testConstructorSetsExpectedValues()
    {
        $exception = new AuthorizationException(
            $url = 'https://test.example.com',
            $result = ['foo' => 'bar'],
            $requestData = 'foobarbaz'
        );
        self::assertEquals($url, $exception->getUrl());
        self::assertEquals($result, $exception->getResult());
        self::assertEquals($requestData, $exception->getRequest());
        self::assertEquals('Authorization has failed!', $exception->getMessage());
    }
}
