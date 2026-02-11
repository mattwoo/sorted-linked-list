<?php

declare(strict_types=1);

namespace Mattwoo\Linkedlist\Tests;

use InvalidArgumentException;
use Mattwoo\Linkedlist\SortedLinkedList;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Mattwoo\Linkedlist\SortedLinkedList
 */
final class SortedLinkedListTest extends TestCase
{
    public function testInitializesEmptyList(): void
    {
        $list = new SortedLinkedList();

        self::assertEquals([], iterator_to_array($list));
    }

    public function testPush(): void
    {
        $list = new SortedLinkedList();
        $list->push(5);
        $list->push(4);
        $list->push(3);
        $list->push(1);
        $list->push(2);

        self::assertEquals([1, 2, 3, 4, 5], iterator_to_array($list));
    }

    public function testDeleteOnEmptyList(): void
    {
        $list = new SortedLinkedList();
        $list->delete(0);

        self::assertSame([], iterator_to_array($list));
    }

    public function testDeleteOnSingleElementList(): void
    {
        $list = new SortedLinkedList(1);
        $list->delete(1);

        self::assertSame([], iterator_to_array($list));
    }

    public function testDeleteOnMultipleElementList(): void
    {
        $list = new SortedLinkedList(1, 2, 3, 4, 5);
        $list->delete(2);

        self::assertSame([1, 3, 4, 5], iterator_to_array($list));

        $list->delete(5);
        $list->delete(4);

        self::assertSame([1, 3], iterator_to_array($list));
    }

    /**
     * @return array<mixed>
     */
    public static function dataProviderTestIsEmpty(): array
    {
        return [
            [new SortedLinkedList(), true],
            [new SortedLinkedList(1), false],
            [new SortedLinkedList(1, 2, 3), false],
        ];
    }

    #[DataProvider('dataProviderTestIsEmpty')]
    public function testIsEmpty(SortedLinkedList $list, bool $expected): void
    {
        self::assertSame($expected, $list->isEmpty());
    }

    /**
     * @return array<mixed>
     */
    public static function dataProviderTestCount(): array
    {
        return [
            [new SortedLinkedList(), 0],
            [new SortedLinkedList(1), 1],
            [new SortedLinkedList(1, 2, 3), 3],
        ];
    }

    #[DataProvider('dataProviderTestCount')]
    public function testCount(SortedLinkedList $list, int $expected): void
    {
        self::assertSame($expected, $list->count());
    }

    public function testFirstReturnsLinkedElement(): void
    {
        $list = new SortedLinkedList(1, 2, 3, 4, 5);

        $first = $list->first();

        self::assertNotNull($first);
        self::assertSame(1, $first->value());
        self::assertSame(2, $first->next()?->value());
        self::assertSame(3, $first->next()->next()?->value());
        self::assertSame(4, $first->next()->next()->next()?->value());
        self::assertSame(5, $first->next()->next()->next()->next()?->value());
    }

    public function testFirstReturnsPushedLinkedElement(): void
    {
        $list = new SortedLinkedList();
        $list->push(1);
        $list->push(2);
        $list->push(3);
        $list->push(4);
        $list->push(5);

        $first = $list->first();

        self::assertNotNull($first);
        self::assertSame(1, $first->value());
        self::assertSame(2, $first->next()?->value());
        self::assertSame(3, $first->next()->next()?->value());
        self::assertSame(4, $first->next()->next()->next()?->value());
        self::assertSame(5, $first->next()->next()->next()->next()?->value());
    }

    public function testInitializesSingleValueList(): void
    {
        $list = new SortedLinkedList();
        $list->add(1);

        self::assertEquals([1], iterator_to_array($list));
    }

    public function testInitializesWithConstructorValues(): void
    {
        $list = new SortedLinkedList(1, 2, 3);

        self::assertEquals([1, 2, 3], iterator_to_array($list));
    }

    public function testRemove(): void
    {
        $list = new SortedLinkedList();
        $list->add(1);
        $list->add(2);
        $list->add(2);
        $list->add(3);
        $list->add(3);

        self::assertEquals([1, 2, 2, 3, 3], iterator_to_array($list));

        $list->remove(2);

        self::assertEquals([1, 3, 3], iterator_to_array($list));

        $list->remove(1);

        self::assertEquals([3, 3], iterator_to_array($list));
    }

    public function testInitializesWithIntValues(): void
    {
        $list = new SortedLinkedList();
        $list->add(2);
        $list->add(3);
        $list->add(1);
        $list->add(5);
        $list->add(4);

        self::assertEquals([1, 2, 3, 4, 5], iterator_to_array($list));
    }

    public function testInitializesWithStringValues(): void
    {
        $list = new SortedLinkedList();
        $list->add('c');
        $list->add('b');
        $list->add('d');
        $list->add('e');
        $list->add('a');

        self::assertEquals(['a', 'b', 'c', 'd', 'e'], iterator_to_array($list));
    }

    public function testThrowsExceptionForMultipleValueTypes(): void
    {
        $list = new SortedLinkedList();
        $list->add(1);

        $this->expectException(InvalidArgumentException::class);
        $expectedMessage = file_get_contents(__DIR__ . '/Expected/testThrowsExceptionForMultipleValueTypes.txt');

        self::assertIsString($expectedMessage);
        $this->expectExceptionMessage($expectedMessage);

        $list->add('a');
    }

    public function testDump(): void
    {
        $list = new SortedLinkedList();
        $list->add(2);
        $list->add(3);
        $list->add(1);
        $list->add(5);
        $list->add(4);

        self::assertSame('1 => 2; 2 => 3; 3 => 4; 4 => 5; 5 => NULL ; ', $list->dump());
    }
}
