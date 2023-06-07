<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Fake;

use DragonBe\Vies\CheckVatResponse;
use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;
use Throwable;

final class ThrowingVies extends Vies
{
    /**
     * @var class-string<Throwable>
     */
    private string $exceptionClass;

    public static function serviceException(): self
    {
        return new self(ViesServiceException::class);
    }

    public static function viesException(): self
    {
        return new self(ViesException::class);
    }

    /**
     * @param class-string<Throwable> $exceptionClass
     */
    private function __construct(string $exceptionClass)
    {
        $this->exceptionClass = $exceptionClass;
    }

    public function validateVat(
        $countryCode,
        $vatNumber,
        string $requesterCountryCode = '',
        string $requesterVatNumber = '',
        string $traderName = '',
        string $traderCompanyType = '',
        string $traderStreet = '',
        string $traderPostcode = '',
        string $traderCity = ''
    ): CheckVatResponse {
        throw new $this->exceptionClass();
    }
}
