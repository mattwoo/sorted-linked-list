<?php

declare(strict_types=1);

namespace Mattwoo\Linkedlist;

final class Element
{
    private function __construct(
        private int|string $value,
        private self|null $next
    ) {
    }

    public static function create(int|string $value): self
    {
        return new self($value, null);
    }

    public function value(): int|string
    {
        return $this->value;
    }

    public function next(): self|null
    {
        return $this->next;
    }

    public function setNext(self|null $next): void
    {
        $this->next = $next;
    }
}
