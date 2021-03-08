<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Request\DeclarationCountries;

use Landingi\BookkeepingBundle\Wfirma\Client\Request;

final class Find implements Request
{
    private string $countryCode;

    public function __construct(string $countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function getContent(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <declaration_countries>
        <parameters>
            <conditions>
                <condition>
                    <field>code</field>    
                    <operator>eq</operator>
                    <value>{$this->countryCode}</value>
                </condition>
            </conditions>
        </parameters>
    </declaration_countries>
</api>
XML;
    }

    public function __toString(): string
    {
        return $this->getContent();
    }
}
