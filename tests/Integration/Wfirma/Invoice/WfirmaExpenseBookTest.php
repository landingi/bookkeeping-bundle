<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExactExpenseDate;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExcludeExpenseSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseBook;
use Landingi\BookkeepingBundle\Bookkeeping\ExpenseSummary;
use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaConditionTransformer;
use Landingi\BookkeepingBundle\Wfirma\Expense\Factory\ExpenseSummaryFactory;
use Landingi\BookkeepingBundle\Wfirma\Expense\WfirmaExpenseBook;

final class WfirmaExpenseBookTest extends IntegrationTestCase
{
    private ExpenseBook $expenseBook;

    public function setUp(): void
    {
        $client = new WfirmaClient(
            new WfirmaCredentials(
                (string) getenv('WFIRMA_API_LOGIN'),
                (string) getenv('WFIRMA_API_PASSWORD'),
                (int) getenv('WFIRMA_API_COMPANY')
            ),
            new WfirmaConditionTransformer()
        );
        $this->expenseBook = new WfirmaExpenseBook(
            $client,
            new ExpenseSummaryFactory(),
        );
    }

    public function testItListsExpenseSummaries(): void
    {
        $conditions = [
            new ExactExpenseDate(new \DateTimeImmutable('2023-05-15')),
            new ExcludeExpenseSeries((string) 'foo'),
        ];
        $summaries = $this->expenseBook->listSummaries(1, ...$conditions);
        $this->assertEquals(1, $summaryArray = $summaries->getAll());
        /** @var ExpenseSummary $summary */
        $summary = $summaryArray[0];
        $this->assertEquals('EXP 1', $summary->getIdentifier());
        $this->assertEquals(2137.00, $summary->getNetPlnValue()->toFloat());
    }
}
