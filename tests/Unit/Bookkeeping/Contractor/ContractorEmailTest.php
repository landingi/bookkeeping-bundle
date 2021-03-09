<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\InvalidEmailAddressException;
use PHPUnit\Framework\TestCase;

final class ContractorEmailTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $email = new ContractorEmail('foo@bar.com');
        self::assertEquals('foo@bar.com', $email->toString());
        self::assertEquals('foo@bar.com', (string) $email);
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        $this->expectException(InvalidEmailAddressException::class);
        new ContractorEmail('');

        $this->expectException(InvalidEmailAddressException::class);
        new ContractorEmail(' ');

        $this->expectException(InvalidEmailAddressException::class);
        new ContractorEmail('invalid email');
    }
}
