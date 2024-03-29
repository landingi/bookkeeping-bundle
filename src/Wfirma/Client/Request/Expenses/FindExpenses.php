<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client\Request\Expenses;

use Landingi\BookkeepingBundle\Wfirma\Client\Request;

final class FindExpenses implements Request
{
    private int $page;
    private string $conditionXml;

    public function __construct(string $conditionXml, int $page = 1)
    {
        $this->conditionXml = $conditionXml;
        $this->page = $page;
    }

    public function getContent(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <expenses>
        <parameters>
            <conditions>
                <and>
                    $this->conditionXml   
                </and>
            </conditions>
            <page>$this->page</page>
        </parameters>
    </expenses>
</api>
XML;
    }

    public function __toString(): string
    {
        return $this->getContent();
    }
}
