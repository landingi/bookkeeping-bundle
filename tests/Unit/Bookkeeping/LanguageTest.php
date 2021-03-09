<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Language;
use PHPUnit\Framework\TestCase;

final class LanguageTest extends TestCase
{
    public function testItIsEnglish(): void
    {
        self::assertTrue((new Language('en'))->isEnglish());
        self::assertTrue((new Language('EN'))->isEnglish());
        self::assertFalse((new Language('pl'))->isEnglish());
        self::assertFalse((new Language('PL'))->isEnglish());
    }

    public function testItIsPolish(): void
    {
        self::assertTrue((new Language('pl'))->isPolish());
        self::assertTrue((new Language('PL'))->isPolish());
        self::assertFalse((new Language('en'))->isPolish());
        self::assertFalse((new Language('EN'))->isPolish());
    }
}
