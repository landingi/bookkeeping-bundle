<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Request\VatCodes;

use Landingi\BookkeepingBundle\Wfirma\Client\Request;

final class Find implements Request
{
    private int $declarationCountryId;
    private int $taxRate;

    public function __construct(int $declarationCountryId, int $taxRate)
    {
        $this->declarationCountryId = $declarationCountryId;
        $this->taxRate = $taxRate;
    }

    public function getContent(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <vat_codes>
        <parameters>
            <conditions>
                <condition>
                    <field>declaration_country_id</field>    
                    <operator>eq</operator>
                    <value>{$this->declarationCountryId}</value>
                </condition>
                <condition>
                    <field>rate</field>    
                    <operator>eq</operator>
                    <value>{$this->taxRate}</value>
                </condition>
            </conditions>
        </parameters>
    </vat_codes>
</api>
XML;
    }

    public function __toString(): string
    {
        return $this->getContent();
    }
}
