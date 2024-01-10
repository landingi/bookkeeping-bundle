<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Wfirma\Invoice\Factory;

use Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax\MemoryIdentifierFactory;
use Landingi\BookkeepingBundle\Wfirma\Contractor\Factory\ContractorFactory;
use Landingi\BookkeepingBundle\Wfirma\Invoice\Factory\InvoiceFactory;
use PHPUnit\Framework\TestCase;

class InvoiceFactoryTest extends TestCase
{
    private InvoiceFactory $sut;
    private ContractorFactory $contractorFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new InvoiceFactory();
        $this->contractorFactory = new ContractorFactory(new MemoryIdentifierFactory());
    }

    /**
     * @dataProvider provideInvoiceData
     * @covers \Landingi\BookkeepingBundle\Bookkeeping\Invoice::getTotalValue()
     */
    public function testCreatesInvoiceFromApiDataWithProperlyRoundedAmounts(
        array $invoiceData,
        array $contractorData
    ): void {
        $invoice = $this->sut->getInvoiceFromApiData(
            $invoiceData,
            $this->contractorFactory->getContractor($contractorData)
        );

        // Total value equals amount returned from API, test integer rounding of floating point numbers
        $this->assertEquals((float) $invoiceData['total'], $invoice->getTotalValue()->toFloat());

        // Total net equals sum of items
        $this->assertEquals($invoice->getNetPlnValue()->toFloat(), $invoice->getMoneyValue());
    }

    public function provideInvoiceData(): \Iterator
    {
        yield 'Invoice with float total value' => [
            [
                'id' => '1',
                'series' => [
                    'id' => 1,
                ],
                'description' => 'Test',
                'fullnumber' => '1/2021',
                'total' => 8.12,
                'netto' => 6.60,
                'invoicecontents' => [
                    [
                        'invoicecontent' => [
                            'name' => 'Test',
                            'price' => 6.60,
                            'vat_code' => [
                                'id' => '1',
                            ],
                            'count' => 1,
                        ],
                    ],
                ],
                'currency' => 'PLN',
                'date' => '2021-01-01',
                'paymentdate' => '2021-01-01',
                'disposaldate' => '2021-01-01',
                'translation_language' => 0,
            ],
            [
                'id' => '1',
                'name' => 'Test',
                'email' => 'test@example.com',
                'street' => 'Test',
                'zip' => '00-000',
                'city' => 'Test',
                'country' => 'PL',
            ]
        ];
    }
}
