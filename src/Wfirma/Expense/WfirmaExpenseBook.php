<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Expense;

use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseBook;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Expense\Factory\ExpenseSummaryFactory;

final class WfirmaExpenseBook implements ExpenseBook
{
    private const EXPENSES_FIND_URL = '/expenses/find';

    private WfirmaClient $client;
    private ExpenseSummaryFactory $summaryFactory;

    public function __construct(WfirmaClient $client, ExpenseSummaryFactory $summaryFactory)
    {
        $this->client = $client;
        $this->summaryFactory = $summaryFactory;
    }

    public function listSummaries(int $page, ExpenseCondition ...$conditions): Collection
    {
        $result = $this->client->findExpenses(self::EXPENSES_FIND_URL, $page, ...$conditions);
        $summaryCollection = new Collection(
            array_map(
                function (array $expenseResult) {
                    return $this->summaryFactory->getSummaryFromApiData($expenseResult['expense']);
                },
                array_filter($result['expenses'], static function (array $expenseResult) {
                    return false === empty($expenseResult['expense']);
                })
            )
        );

        if ($result['expenses']['parameters']['total'] > $page * (int) $result['expenses']['parameters']['limit']) {
            $summaryCollection->merge($this->listSummaries($page + 1, ...$conditions));
        }

        return $summaryCollection;
    }
}
