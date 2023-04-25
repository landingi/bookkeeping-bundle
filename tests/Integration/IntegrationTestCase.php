<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration;

use PHPUnit\Framework\TestCase;

// We call external APIs in all integration tests, wFirma has a low request rate limit so we need to limit ourselves
class IntegrationTestCase extends TestCase
{
    private const HALF_SECOND_IN_MICROSECONDS = 500000;

    protected function tearDown(): void
    {
        usleep(self::HALF_SECOND_IN_MICROSECONDS);
    }
}
