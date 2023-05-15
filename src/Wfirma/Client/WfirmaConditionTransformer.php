<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

use InvalidArgumentException;
use Landingi\BookkeepingBundle\Bookkeeping\Collection\CollectionCondition;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExactExpenseDate;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExcludeExpenseSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExactDate;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExactInvoiceNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExcludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\IncludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\InvoiceCondition;

final class WfirmaConditionTransformer
{
    public function toXml(CollectionCondition $condition): string
    {
        if ($condition instanceof InvoiceCondition) {
            return $this->buildInvoiceCondition($condition);
        }

        if ($condition instanceof ExpenseCondition) {
            return $this->buildExpenseCondition($condition);
        }

        throw new InvalidArgumentException('Provided collection condition is not supported');
    }

    private function buildInvoiceCondition(InvoiceCondition $condition): string
    {
        if ($condition instanceof ExactDate) {
            return $this->buildExactDateXml($condition);
        }

        if ($condition instanceof ExactInvoiceNumber) {
            return $this->buildExactNumberXml($condition);
        }

        if ($condition instanceof IncludeSeries) {
            return $this->buildIncludeSeriesXml($condition);
        }

        if ($condition instanceof ExcludeSeries) {
            return $this->buildExcludeSeriesXml($condition);
        }

        throw new InvalidArgumentException('Provided invoice condition is not supported');
    }

    private function buildExpenseCondition(ExpenseCondition $condition): string
    {
        if ($condition instanceof ExactExpenseDate) {
            return $this->buildExactDateXml($condition);
        }

        if ($condition instanceof ExcludeExpenseSeries) {
            return $this->buildExcludeSeriesXml($condition);
        }

        throw new InvalidArgumentException('Provided expense condition is not supported');
    }

    private function buildExactDateXml(CollectionCondition $condition): string
    {
        return <<<XML
<condition>
    <field>date</field>
    <operator>eq</operator>
    <value>{$condition}</value>
</condition>
XML;
    }

    private function buildExactNumberXml(CollectionCondition $condition): string
    {
        return <<<XML
<condition>
    <field>fullnumber</field>
    <operator>eq</operator>
    <value>{$condition}</value>
</condition>
XML;
    }

    private function buildIncludeSeriesXml(CollectionCondition $condition): string
    {
        return <<<XML
<condition>
    <field>fullnumber</field>
    <operator>like</operator>
    <value>%$condition%</value>
</condition>
XML;
    }

    private function buildExcludeSeriesXml(CollectionCondition $condition): string
    {
        return <<<XML
<condition>
    <field>fullnumber</field>
    <operator>not like</operator>
    <value>%$condition%</value>
</condition>
XML;
    }
}
