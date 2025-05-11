<?php

declare(strict_types=1);

namespace Database\ORM\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, Countable, ArrayAccess
{
    /** @var array<int,mixed> */
    protected array $items;

    /**
     * @param array<int,mixed> $items
     */
    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    public function all(): array
    {
        return $this->items;
    }

    /** @return Traversable */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /** @return int */
    public function count(): int
    {
        return count($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function map(callable $cb): self
    {
        return new self(array_map($cb, $this->items));
    }

    public function filter(callable $cb): self
    {
        return new self(array_filter($this->items, $cb));
    }

    public function sort(callable $cb): self
    {
        $items = $this->items;
        usort($items, $cb);
        return new self($items);
    }

    public function toArray(): array
    {
        return array_map(fn($m) => method_exists($m, 'toArray') ? $m->toArray() : $m, $this->items);
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function push(mixed $item): self
    {
        $new = clone $this;
        $new->items[] = $item;
        return $new;
    }

    public function sum(string $key): int
    {
        $sum = 0;
        foreach ($this->items as $item) {
            if (is_array($item)) {
                $sum += (int)($item[$key] ?? 0);
            } elseif (is_object($item)) {
                try {
                    $sum += (int)$item->{$key};
                } catch (\Error $e) {
                    // We do not add anything
                }
            }
        }
        return $sum;
    }
}
