<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Wfirma\Client\Credentials\WfirmaCredentials;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceFactory;
use PHPUnit\Framework\TestCase;

final class WfirmaInvoiceBookTest extends TestCase
{
    public function testInvoiceWorkflow(): void
    {
        $book = new WfirmaInvoiceBook(
            new WfirmaClient(
                new WfirmaCredentials(
                    '',
                    '',

                )
            ),
            new InvoiceFactory(),
            new ContractorFactory()
        );

        $invoice = $book->find(new InvoiceIdentifier('127808776'));
    }
}