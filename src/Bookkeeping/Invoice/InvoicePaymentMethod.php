<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

final class InvoicePaymentMethod
{
    private const METHOD_CASH = 'cash';
    private const METHOD_COD = 'cod';
    private const METHOD_COMPENSATION = 'compensation';
    private const METHOD_PAYMENT_CARD = 'payment_card';
    private const METHOD_TRANSFER = 'transfer';
    private string $method;

    /**
     * @throws InvoiceException
     */
    public function __construct(string $method)
    {
        $availableMethods = [
            self::METHOD_CASH,
            self::METHOD_COD,
            self::METHOD_COMPENSATION,
            self::METHOD_PAYMENT_CARD,
            self::METHOD_TRANSFER,
        ];

        if (false === in_array($method, $availableMethods, true)) {
            throw new InvoiceException("Unsupported payment method provided: $method");
        }

        $this->method = $method;
    }

    public function __toString(): string
    {
        return $this->method;
    }

    public function isCash(): bool
    {
        return self::METHOD_CASH === $this->method;
    }

    public function isCod(): bool
    {
        return self::METHOD_COD === $this->method;
    }

    public function isCompensation(): bool
    {
        return self::METHOD_COMPENSATION === $this->method;
    }

    public function isPaymentCard(): bool
    {
        return self::METHOD_PAYMENT_CARD === $this->method;
    }

    public function isTransfer(): bool
    {
        return self::METHOD_TRANSFER === $this->method;
    }
}
