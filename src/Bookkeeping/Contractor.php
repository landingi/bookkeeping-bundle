<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;

interface Contractor
{
    public function getIdentifier(): ContractorIdentifier;
    public function isEuropeanUnionCitizen(): bool;
    public function print(Media $media): Media;
}
