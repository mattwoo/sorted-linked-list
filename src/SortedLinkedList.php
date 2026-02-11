<?php

declare(strict_types=1);

namespace Mattwoo\Linkedlist;

use Generator;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @template-implements IteratorAggregate<string|int>
 */
final class SortedLinkedList implements IteratorAggregate
{
    private Element|null $firstElement;

    public function __construct(int|string ...$elements)
    {
        $this->firstElement = null;
        if ($elements !== []) {
            $this->setupList($elements);
        }
    }

    public function add(string|int $newValue): void
    {
        // Approach 1 - convert to array and sort it
        $this->setupList(array_merge(iterator_to_array($this), [$newValue]));
    }

    public function push(string|int $newValue): void
    {
        // Approach 2 - find a first element > current one and push it in between
        // Better in terms of performance
        $newElement = Element::create($newValue);

        if ($this->firstElement === null) {
            $this->firstElement = $newElement;
            return;
        }

        if ($newValue < $this->firstElement->value()) {
            $newElement->setNext($this->firstElement);
            $this->firstElement = $newElement;
            return;
        }

        $current = $this->firstElement;

        while ($current->next() !== null && $newValue > $current->next()->value()) {
            $current = $current->next();
        }

        $newElement->setNext($current->next());
        $current->setNext($newElement);
    }

    public function remove(string|int $valueToRemove): void
    {
        // Approach 1 - remove an element, convert to array and sort it
        $this->setupList(
            array_filter(iterator_to_array($this), static fn ($item) => $item !== $valueToRemove)
        );
    }

    public function delete(string|int $valueToRemove): void
    {
        // Approach 2 - find an element === current one and modify the chain
        // Better in terms of performance
        if ($this->firstElement === null) {
            return;
        }

        // First element must be removed
        if ($this->firstElement->value() === $valueToRemove) {
            $this->firstElement = $this->firstElement->next();
            return;
        }

        $current = $this->firstElement;
        while ($current->next() !== null && $current->next()->value() !== $valueToRemove) {
            $current = $current->next();
        }

        if ($current->next() === null) {
            return;
        }

        $current->setNext($current->next()->next());
    }

    public function count(): int
    {
        return count(iterator_to_array($this));
    }

    public function first(): Element|null
    {
        return $this->firstElement;
    }

    /**
     * @param array<string|int> $values
     */
    private function setupList(array $values): void
    {
        sort($values);
        $valueTypes  = array_map('gettype', $values);
        $uniqueTypes = array_unique($valueTypes);

        if (count($uniqueTypes) > 1) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" expects same type for all values, "%s" given.',
                    __CLASS__,
                    implode(', ', $uniqueTypes)
                )
            );
        }

        $count   = count($values);
        $current = Element::create($values[0]);

        $this->firstElement = $current;

        for ($i = 1; $i < $count; $i++) {
            $nextKey = $i;
            if (array_key_exists($nextKey, $values) && $current !== null) {
                $current->setNext(Element::create($values[$nextKey]));
                $current = $current->next();
            }
        }
    }

    public function isEmpty(): bool
    {
        return $this->firstElement === null;
    }

    public function dump(): string
    {
        $out     = '';
        $current = $this->firstElement;
        while ($current !== null) {
            $out     .= $current->value() . ' => ' . ($current->next()?->value() ?? 'NULL ') . '; ';
            $current = $current->next();
        }

        return $out;
    }

    /**
     * @return Generator<int|string>
     */
    public function getIterator(): Generator
    {
        $current = $this->firstElement;
        while ($current !== null) {
            yield $current->value();
            $current = $current->next();
        }
    }
}
