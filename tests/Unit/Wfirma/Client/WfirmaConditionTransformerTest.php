<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client;

use InvalidArgumentException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExactDate;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExcludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\IncludeSeries;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaConditionTransformer;
use PHPUnit\Framework\TestCase;

class WfirmaConditionTransformerTest extends TestCase
{
    private WfirmaConditionTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new WfirmaConditionTransformer();
    }

    public function testToXmlTransformsExactDateCondition(): void
    {
        $datetime = new \DateTimeImmutable('now');
        $this->assertEquals(
            <<<XML
<condition>
    <field>date</field>
    <operator>eq</operator>
    <value>{$datetime->format('Y-m-d')}</value>
</condition>
XML,
            $this->transformer->toXml(new ExactDate($datetime))
        );
    }

    public function testToXmlTransformsIncludeSeriesCondition(): void
    {
        $this->assertEquals(
            <<<XML
<condition>
    <field>fullnumber</field>
    <operator>like</operator>
    <value>%foo%</value>
</condition>
XML,
            $this->transformer->toXml(new IncludeSeries('foo'))
        );
    }

    public function testToXmlTransformsExcludeSeriesCondition(): void
    {
        $this->assertEquals(
            <<<XML
<condition>
    <field>fullnumber</field>
    <operator>not like</operator>
    <value>%foo%</value>
</condition>
XML,
            $this->transformer->toXml(new ExcludeSeries('foo'))
        );
    }

    public function testOtherConditionThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Provided condition is not supported');

        $this->transformer->toXml(new class() implements Condition {
            public function __toString() : string
            {
                return '';
            }
        });
    }
}
