<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use Generator;
use Landingi\BookkeepingBundle\Fake;
use PHPUnit\Framework\TestCase;

final class InvoiceItemCollectionTest extends TestCase
{
    /**
     * @dataProvider twoCollectionsToMergeProvider
     */
    public function testMergingCollections(
        InvoiceItemCollection $firstCollection,
        InvoiceItemCollection $secondCollection,
        InvoiceItemCollection $expectedCollection
    ): void {
        self::assertEquals($expectedCollection->getAll(), $firstCollection->merge($secondCollection)->getAll());
    }

    public function twoCollectionsToMergeProvider(): Generator
    {
        yield 'merging collections with one item' => [
            new InvoiceItemCollection([$firstItem = Fake\InvoiceItem::createWithoutPrice('test1')]),
            new InvoiceItemCollection([$secondItem = Fake\InvoiceItem::createWithoutPrice('test2')]),
            new InvoiceItemCollection([$firstItem, $secondItem]),
        ];

        yield 'merging collections when one is empty' => [
            new InvoiceItemCollection([$firstItem = Fake\InvoiceItem::createWithoutPrice('test1')]),
            new InvoiceItemCollection([]),
            new InvoiceItemCollection([$firstItem]),
        ];

        yield 'merging collection with various number of items' => [
            new InvoiceItemCollection([$firstItem = Fake\InvoiceItem::createWithoutPrice('test1')]),
            new InvoiceItemCollection([
                $secondItem = Fake\InvoiceItem::createWithoutPrice('test2'),
                $thirdItem = Fake\InvoiceItem::createWithoutPrice('test3'),
            ]),
            new InvoiceItemCollection([$firstItem, $secondItem, $thirdItem]),
        ];
    }

    /**
     * @dataProvider threeCollectionsToMergeProvider
     */
    public function testChainMerging(
        InvoiceItemCollection $firstCollection,
        InvoiceItemCollection $secondCollection,
        InvoiceItemCollection $thirdCollection,
        InvoiceItemCollection $expectedCollection
    ): void {
        self::assertEquals(
            $expectedCollection->getAll(),
            $firstCollection
                ->merge($secondCollection)
                ->merge($thirdCollection)
                ->getAll()
        );
    }

    public function threeCollectionsToMergeProvider(): Generator
    {
        yield 'merging three collections contain single item' => [
            new InvoiceItemCollection([$firstItem = Fake\InvoiceItem::createWithoutPrice('test1')]),
            new InvoiceItemCollection([$secondItem = Fake\InvoiceItem::createWithoutPrice('test2')]),
            new InvoiceItemCollection([$thirdItem = Fake\InvoiceItem::createWithoutPrice('test3')]),
            new InvoiceItemCollection([$firstItem, $secondItem, $thirdItem]),
        ];

        yield 'merging three collections when one is empty' => [
            new InvoiceItemCollection([$firstItem = Fake\InvoiceItem::createWithoutPrice('test1')]),
            new InvoiceItemCollection([]),
            new InvoiceItemCollection([$secondItem = Fake\InvoiceItem::createWithoutPrice('test2')]),
            new InvoiceItemCollection([$firstItem, $secondItem]),
        ];

        yield 'merging three collection with various number of items' => [
            new InvoiceItemCollection([$firstItem = Fake\InvoiceItem::createWithoutPrice('test1')]),
            new InvoiceItemCollection([
                $secondItem = Fake\InvoiceItem::createWithoutPrice('test2'),
                $thirdItem = Fake\InvoiceItem::createWithoutPrice('test3'),
                $fourthItem = Fake\InvoiceItem::createWithoutPrice('test4'),
            ]),
            new InvoiceItemCollection([$fifthItem = Fake\InvoiceItem::createWithoutPrice('test5')]),
            new InvoiceItemCollection([$firstItem, $secondItem, $thirdItem, $fourthItem, $fifthItem]),
        ];
    }
}
