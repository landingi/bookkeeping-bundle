<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Request\Invoices;

use Landingi\BookkeepingBundle\Wfirma\Client\Request\Invoices\FindInvoices;
use PHPUnit\Framework\TestCase;

class FindInvoicesTest extends TestCase
{
    public function testGetContentGeneratesExpectedXml(): void
    {
        $request = new FindInvoices(
            $conditionXml = '<condition></condition>',
            $page = 14
        );
        $this->assertEquals(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <parameters>
            <conditions>
                <and>
                    $conditionXml   
                </and>
            </conditions>
            <page>$page</page>
        </parameters>
    </invoices>
</api>
XML,
            $request->getContent()
        );
        $this->assertEquals($request->getContent(), (string) $request);
    }
}
