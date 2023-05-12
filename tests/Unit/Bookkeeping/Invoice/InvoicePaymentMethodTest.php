<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice;

use Generator;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoicePaymentMethod;
use PHPUnit\Framework\TestCase;

class InvoicePaymentMethodTest extends TestCase
{
    /**
     * @dataProvider validMethods
     */
    public function testToStringReturnsPaymentMethod(string $method): void
    {
        $paymentMethod = new InvoicePaymentMethod($method);
        $this->assertEquals($method, (string) $paymentMethod);
        $functionName = sprintf('is%s', lcfirst(str_replace('_', '', ucwords($method, '_'))));
        $this->assertTrue($paymentMethod->{$functionName}());
    }

    public function testInvalidMethodThrowsException(): void
    {
        $this->expectException(InvoiceException::class);
        $this->expectExceptionMessage('Unsupported payment method provided: blik');

        (new InvoicePaymentMethod('blik'));
    }

    public function validMethods(): Generator
    {
        yield ['cash'];
        yield ['cod'];
        yield ['compensation'];
        yield ['payment_card'];
        yield ['transfer'];
    }
}
