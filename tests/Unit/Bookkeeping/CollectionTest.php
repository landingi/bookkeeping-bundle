<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping;

use Generator;
use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use Landingi\BookkeepingBundle\Unit\Fake\InvoiceItem;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    /**
     * @dataProvider twoCollectionsToMergeProvider
     */
    public function testMergingCollections(
        Collection $firstCollection,
        Collection $secondCollection,
        Collection $expectedCollection
    ): void {
        self::assertEquals($expectedCollection->getAll(), $firstCollection->merge($secondCollection)->getAll());
    }

    public function twoCollectionsToMergeProvider(): Generator
    {
        yield 'merging collections with one item' => [
            new Collection([$firstItem = InvoiceItem::createWithoutPrice('test1')]),
            new Collection([$secondItem = InvoiceItem::createWithoutPrice('test2')]),
            new Collection([$firstItem, $secondItem]),
        ];

        yield 'merging collections when one is empty' => [
            new Collection([$firstItem = InvoiceItem::createWithoutPrice('test1')]),
            new Collection([]),
            new Collection([$firstItem]),
        ];

        yield 'merging collection with various number of items' => [
            new Collection([$firstItem = InvoiceItem::createWithoutPrice('test1')]),
            new Collection([
                $secondItem = InvoiceItem::createWithoutPrice('test2'),
                $thirdItem = InvoiceItem::createWithoutPrice('test3'),
            ]),
            new Collection([$firstItem, $secondItem, $thirdItem]),
        ];
    }

    /**
     * @dataProvider threeCollectionsToMergeProvider
     */
    public function testChainMerging(
        Collection $firstCollection,
        Collection $secondCollection,
        Collection $thirdCollection,
        Collection $expectedCollection
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
            new Collection([$firstItem = InvoiceItem::createWithoutPrice('test1')]),
            new Collection([$secondItem = InvoiceItem::createWithoutPrice('test2')]),
            new Collection([$thirdItem = InvoiceItem::createWithoutPrice('test3')]),
            new Collection([$firstItem, $secondItem, $thirdItem]),
        ];

        yield 'merging three collections when one is empty' => [
            new Collection([$firstItem = InvoiceItem::createWithoutPrice('test1')]),
            new Collection([]),
            new Collection([$secondItem = InvoiceItem::createWithoutPrice('test2')]),
            new Collection([$firstItem, $secondItem]),
        ];

        yield 'merging three collection with various number of items' => [
            new Collection([$firstItem = InvoiceItem::createWithoutPrice('test1')]),
            new Collection([
                $secondItem = InvoiceItem::createWithoutPrice('test2'),
                $thirdItem = InvoiceItem::createWithoutPrice('test3'),
                $fourthItem = InvoiceItem::createWithoutPrice('test4'),
            ]),
            new Collection([$fifthItem = InvoiceItem::createWithoutPrice('test5')]),
            new Collection([$firstItem, $secondItem, $thirdItem, $fourthItem, $fifthItem]),
        ];
    }
}
