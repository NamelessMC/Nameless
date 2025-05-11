<?php

declare(strict_types=1);

namespace Database\ORM\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, Countable, ArrayAccess
{
    /** @var array<int, mixed> */
    protected array $items;

    /**
     * @param array<int, mixed> $items
     */
    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    /** @return mixed[] */
    public function all(): array
    {
        return $this->items;
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

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

    /**
     * @param callable(mixed): mixed $cb
     * @return $this
     */
    public function map(callable $cb): self
    {
        return new self(array_map($cb, $this->items));
    }

    /**
     * @param callable(mixed): bool $cb
     * @return $this
     */
    public function filter(callable $cb): self
    {
        return new self(array_filter($this->items, $cb));
    }

    /**
     * @param callable(mixed, mixed): int $cb
     * @return $this
     */
    public function sort(callable $cb): self
    {
        $items = $this->items;
        usort($items, $cb);

        return new self($items);
    }

    /**
     * Convert each item via its toArray() if available.
     *
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->items as $item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                $result[] = $item->toArray();
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * Convert collection to JSON.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Push an item onto the end of the collection.
     */
    public function push(mixed $item): self
    {
        $new = clone $this;
        $new->items[] = $item;
        return $new;
    }

    /**
     * Sum up the values of a given key.
     *
     * @param string $key
     * @return int
     */
    public function sum(string $key): int
    {
        $sum = 0;
        foreach ($this->items as $item) {
            if (is_array($item)) {
                $sum += (int) ($item[$key] ?? 0);
            } elseif (is_object($item)) {
                $sum += (int) ($item->{$key} ?? 0);
            }
        }
        return $sum;
    }
}
