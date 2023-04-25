<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration;

use PHPUnit\Framework\TestCase;

// We call external APIs in all integration tests, wFirma has a low request rate limit so we need to limit ourselves
class IntegrationTestCase extends TestCase
{
    protected function tearDown(): void
    {
        sleep(1);
    }
}
