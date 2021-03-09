<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Request\Invoice;

use Landingi\BookkeepingBundle\Wfirma\Client\Request;

final class Download implements Request
{
    public function getContent(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <parameters>
            <parameter>
                <name>page</name>
                <value>invoice</value>
            </parameter>
        </parameters>
    </invoices>
</api>
XML;
    }

    public function __toString(): string
    {
        return $this->getContent();
    }
}
