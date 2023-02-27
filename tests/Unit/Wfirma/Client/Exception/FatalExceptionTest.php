<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\Exception\FatalException;
use PHPUnit\Framework\TestCase;

class FatalExceptionTest extends TestCase
{
    public function testConstructorSetsExpectedValues()
    {
        $exception = new FatalException(
            $url = 'https://test.example.com',
            $result = ['foo' => 'bar'],
            $requestData = 'foobarbaz'
        );
        self::assertEquals($url, $exception->getUrl());
        self::assertEquals($result, $exception->getResult());
        self::assertEquals($requestData, $exception->getRequest());
        self::assertEquals('Internal error in the external system', $exception->getMessage());
    }
}
