<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client\Request\Expenses;

use Landingi\BookkeepingBundle\Wfirma\Client\Request\Expenses\FindExpenses;
use PHPUnit\Framework\TestCase;

class FindExpensesTest extends TestCase
{
    public function testGetContentGeneratesExpectedXml(): void
    {
        $request = new FindExpenses(
            $conditionXml = '<condition></condition>',
            $page = 14
        );
        $this->assertEquals(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <expenses>
        <parameters>
            <conditions>
                <and>
                    $conditionXml   
                </and>
            </conditions>
            <page>$page</page>
        </parameters>
    </expenses>
</api>
XML,
            $request->getContent()
        );
        $this->assertEquals($request->getContent(), (string) $request);
    }
}
