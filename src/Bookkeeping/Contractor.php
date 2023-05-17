<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;

interface Contractor
{
    public function getIdentifier(): ContractorIdentifier;
    public function getEmail(): ContractorEmail;
    public function changeEmail(ContractorEmail $email): void;
    public function isPolish(): bool;
    public function isEuropeanUnionCitizen(): bool;
    public function isEuropeanUnionCompany(): bool;
    public function print(Media $media): Media;
}
