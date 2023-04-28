<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Client;

use InvalidArgumentException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExactDate;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExcludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\IncludeSeries;

final class WfirmaConditionTransformer
{
    public function toXml(Condition $condition): string
    {
        if ($condition instanceof ExactDate) {
            return $this->buildExactDateXml($condition);
        }

        if ($condition instanceof IncludeSeries) {
            return $this->buildIncludeSeriesXml($condition);
        }

        if ($condition instanceof ExcludeSeries) {
            return $this->buildExcludeSeriesXml($condition);
        }

        throw new InvalidArgumentException('Provided condition is not supported');
    }

    private function buildExactDateXml(ExactDate $condition): string
    {
        return <<<XML
        <condition>
            <field>date</field>
            <operator>eq</operator>
            <value>{$condition}</value>
        </condition>            
        XML;
    }


    private function buildIncludeSeriesXml(IncludeSeries $condition): string
    {
        return <<<XML
        <condition>
            <field>fullnumber</field>
            <operator>like</operator>
            <value>%$condition%</value>
        </condition>
        XML;
    }

    private function buildExcludeSeriesXml(ExcludeSeries $condition): string
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
