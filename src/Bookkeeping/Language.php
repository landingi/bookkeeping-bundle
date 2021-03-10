<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use function strtoupper;

final class Language
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = strtoupper($code);
    }

    public function isEnglish(): bool
    {
        return 'EN' === $this->code;
    }

    public function isPolish(): bool
    {
        return 'PL' === $this->code;
    }
}
