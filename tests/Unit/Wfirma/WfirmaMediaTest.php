<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma;

use Landingi\BookkeepingBundle\Wfirma\WfirmaMedia;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SimpleXMLElement;

final class WfirmaMediaTest extends TestCase
{
    private WfirmaMedia $media;
    private string $xml;

    protected function setUp(): void
    {
        $this->xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
</root>
XML;
        $this->media = new WfirmaMedia(new SimpleXMLElement($this->xml));
    }

    public function testSimpleXML(): void
    {
        self::assertXmlStringEqualsXmlString($this->xml, (string) (new SimpleXMLElement($this->xml))->asXML());
    }

    public function testItConvertsToString(): void
    {
        $this->media->with('child', 'value');
        $this->media->with('child', '')->with('child', 'value');
        $this->media->with('child', '');
        $this->media->with('child', 'Company & World');

        self::assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <child>value</child>
    <child>
        <child>value</child>    
    </child>
    <child></child>
    <child>Company &amp; World</child>
</root>
XML,
            $this->media->toString()
        );
    }

    public function testItThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->media->with('', '');
    }
}
