<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Wfirma\Invoice;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorEmail;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExactExpenseDate;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition\ExcludeExpenseSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseBook;
use Landingi\BookkeepingBundle\Bookkeeping\ExpenseSummary;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExactDate;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\ExcludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition\IncludeSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceBook;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use Landingi\BookkeepingBundle\Bookkeeping\InvoiceSummary;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax\MemoryIdentifierFactory;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaConditionTransformer;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\WfirmaContractorBook;
use Landingi\BookkeepingBundle\Wfirma\Expense\Factory\ExpenseSummaryFactory;
use Landingi\BookkeepingBundle\Wfirma\Expense\WfirmaExpenseBook;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceSummaryFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceBook;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItemCollection;
use Landingi\BookkeepingBundle\Wfirma\WfirmaInvoice;

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
