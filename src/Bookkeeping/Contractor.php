<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;

interface Contractor
{
    /**
     * This is a representation of a physical person that lives in the European Union.
     */
    public function isEuropeanUnionCitizen(): bool;
    public function isEuropeanUnionCompany(): bool;
    public function print(Media $media): Media;
    public function getIdentifier(): ContractorIdentifier;
}
