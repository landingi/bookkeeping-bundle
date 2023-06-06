<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use function in_array;
use function strlen;

final class Country
{
    /**
     * The EU uses EL for Greece for VAT purposes, even though it's Alpha-2 code is GR.
     */
    private const EU_VAT_CODE_TO_ALPHA2 = [
        'EL' => 'GR',
    ];

    private const EUROPEAN_UNION_VAT_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'EL',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PL',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];
    private string $alpha2Code;

    /**
     * @throws AddressException
     */
    public function __construct(string $alpha2Code)
    {
        if (2 !== strlen($alpha2Code)) {
            throw new AddressException('Country code must be a 2 character string!');
        }

        $this->alpha2Code = $alpha2Code;
    }

    /**
     * This may return different results than toString(), specifically for Greece.
     */
    public function getAlpha2Code(): string
    {
        return self::EU_VAT_CODE_TO_ALPHA2[$this->alpha2Code] ?? $this->alpha2Code;
    }

    public function isEuropeanUnion(): bool
    {
        return in_array($this->alpha2Code, self::EUROPEAN_UNION_VAT_COUNTRIES);
    }

    public function isPoland(): bool
    {
        return 'PL' === $this->alpha2Code;
    }

    public function toString(): string
    {
        return $this->alpha2Code;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
