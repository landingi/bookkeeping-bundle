<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Client;

use InvalidArgumentException;
use Landingi\BookkeepingBundle\Bookkeeping\Collection\CollectionCondition;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExactExpenseDate;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExcludeExpenseSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\InvoiceCondition;
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

    /**
     * @dataProvider provideValidCondition
     */
    public function testToXmlTransformsConditions(CollectionCondition $condition, string $expectedXml): void
    {
        $this->assertEquals($expectedXml, $this->transformer->toXml($condition));
    }

    public function provideValidCondition(): \Generator
    {
        $now = new \DateTimeImmutable('now');

        yield 'Invoice exact date condition' => [
            new ExactDate($now),
            <<<XML
<condition>
    <field>date</field>
    <operator>eq</operator>
    <value>{$now->format('Y-m-d')}</value>
</condition>
XML
        ];
        yield 'Invoice include series condition' => [
            new IncludeSeries('foo'),
            <<<XML
<condition>
    <field>fullnumber</field>
    <operator>like</operator>
    <value>%foo%</value>
</condition>
XML
        ];
        yield 'Invoice exclude series condition' => [
            new ExcludeSeries('foo'),
            <<<XML
<condition>
    <field>fullnumber</field>
    <operator>not like</operator>
    <value>%foo%</value>
</condition>
XML
        ];
        yield 'Expense exact date condition' => [
            new ExactExpenseDate($now),
            <<<XML
<condition>
    <field>date</field>
    <operator>eq</operator>
    <value>{$now->format('Y-m-d')}</value>
</condition>
XML
        ];
        yield 'Expense exclude series date condition' => [
            new ExcludeExpenseSeries('foo'),
            <<<XML
<condition>
    <field>fullnumber</field>
    <operator>not like</operator>
    <value>%foo%</value>
</condition>
XML
        ];
    }

    /**
     * @dataProvider provideInvalidCondition
     */
    public function testOtherConditionThrowsException(CollectionCondition $condition, string $expectedMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        $this->transformer->toXml($condition);
    }

    public function provideInvalidCondition(): \Generator
    {
        yield 'Generic condition' => [
            new class() implements CollectionCondition {
                public function __toString(): string
                {
                    return '';
                }
            },
            'Provided collection condition is not supported'
        ];
        yield 'Invoice condition' => [
            new class() implements InvoiceCondition {
                public function __toString(): string
                {
                    return '';
                }
            },
            'Provided invoice condition is not supported'
        ];
        yield 'Expense condition' => [
            new class() implements ExpenseCondition {
                public function __toString(): string
                {
                    return '';
                }
            },
            'Provided expense condition is not supported'
        ];
    }
}
