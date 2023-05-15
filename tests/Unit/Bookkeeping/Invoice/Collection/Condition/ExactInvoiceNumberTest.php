<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice\Collection\Condition;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExactInvoiceNumber;
use PHPUnit\Framework\TestCase;

class ExactInvoiceNumberTest extends TestCase
{
    public function testToString(): void
    {
        $this->assertEquals('FV foo', (string) new ExactInvoiceNumber('FV foo'));
    }
}
