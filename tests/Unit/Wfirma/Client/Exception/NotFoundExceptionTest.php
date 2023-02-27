<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Exception;

use Landingi\BookkeepingBundle\Wfirma\Client\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class NotFoundExceptionTest extends TestCase
{
    public function testConstructorSetsExpectedValues(): void
    {
        $exception = new NotFoundException(
            $url = 'https://test.example.com',
            $result = ['foo' => 'bar'],
            $requestData = 'foobarbaz'
        );
        self::assertEquals($url, $exception->getUrl());
        self::assertEquals($result, $exception->getResult());
        self::assertEquals($requestData, $exception->getRequest());
        self::assertEquals('External system returns 404 code', $exception->getMessage());
    }
}
