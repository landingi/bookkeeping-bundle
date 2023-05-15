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

    /**
     * The predefined expense in this test should be replaced with a created one, whenever
     * the option to add an expense is added to the bundle.
     */
    public function testItListsExpenseSummaries(): void
    {
        $conditions = [
            new ExactExpenseDate(new \DateTimeImmutable('2023-05-15')),
            new ExcludeExpenseSeries((string) 'foo'),
        ];
        $summaries = $this->expenseBook->listSummaries(1, ...$conditions);
        $this->assertCount(1, $summaryArray = $summaries->getAll());
        /** @var ExpenseSummary $summary */
        $summary = $summaryArray[0];
        $this->assertEquals('82866659', $summary->getIdentifier());
        $this->assertEquals(2137.00, $summary->getNetPlnValue()->toFloat());
    }
}
