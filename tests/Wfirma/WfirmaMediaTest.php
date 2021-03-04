<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

final class WfirmaMediaTest extends TestCase
{
    private WfirmaMedia $media;
    private string $xml;

    protected function setUp(): void
    {
        $this->xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
</api>
XML;
        $this->media = new WfirmaMedia(new SimpleXMLElement($this->xml));
    }

    public function testItIsValidMedia(): void
    {
        self::assertXmlStringEqualsXmlString($this->xml, (string) (new SimpleXMLElement($this->xml))->asXML());
    }

    public function testItGetsXML(): void
    {
        /*
        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<api>
    <invoices>
        <invoice>
            <contractor>
                <id>100</id>
            </contractor>
            <paymentmethod>transfer</paymentmethod>
            <currency>PLN</currency>
            <alreadypaid_initial>1400</alreadypaid_initial>
            <type>normal</type>
            <date>2020-02-01</date>
            <paymentdate>2020-02-01</paymentdate>
            <description>Description Example</description>
        </invoice>
    </invoices>
</api>
XML,
            $this->media->with('invoices', 'asd')->with('id', 'id')->getXML()
        );
        */
    }
}
