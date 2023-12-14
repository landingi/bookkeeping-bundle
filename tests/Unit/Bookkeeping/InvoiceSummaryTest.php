<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoicePaymentMethod;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use Landingi\BookkeepingBundle\Bookkeeping\InvoiceSummary;
use PHPUnit\Framework\TestCase;

class InvoiceSummaryTest extends TestCase
{
    public function testGettersReturnProperValues(): void
    {
        $summary = new InvoiceSummary(
            new InvoiceIdentifier('123'),
            new InvoiceFullNumber('FV 123/23'),
            new InvoiceTotalValue(10000),
            new InvoiceNetPlnValue(10000),
            new Currency('PLN'),
            new \DateTimeImmutable('2023-05-10'),
            new \DateTimeImmutable('2023-05-11'),
            new \DateTimeImmutable('2023-05-12'),
            new \DateTimeImmutable('2023-05-14'),
            new InvoicePaymentMethod('transfer')
        );
        $this->assertEquals('123', (string) $summary->getIdentifier());
        $this->assertEquals('FV 123/23', (string) $summary->getFullNumber());
        $this->assertEquals(100.00, $summary->getTotalValue()->toFloat());
        $this->assertEquals(100.00, $summary->getNetPlnValue()->toFloat());
        $this->assertEquals('PLN', $summary->getCurrency()->getSymbol());
        $this->assertEquals('2023-05-10', $summary->getCreatedAt()->format('Y-m-d'));
        $this->assertEquals('2023-05-11', $summary->getModifiedAt()->format('Y-m-d'));
        $this->assertEquals('2023-05-12', $summary->getPaidAt()->format('Y-m-d'));
        $this->assertEquals('2023-05-14', $summary->getDisposalDate()->format('Y-m-d'));
        $this->assertTrue($summary->getPaymentMethod()->isTransfer());
    }
}
