<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

final class Language
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = strtoupper($code);
    }

    public function isEnglish(): bool
    {
        return $this->code === 'EN';
    }

    public function isPolish(): bool
    {
        return $this->code === 'PL';
    }
}
