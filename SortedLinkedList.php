<?php

require_once 'ListType.php';
require_once 'Exception/TypeException.php';

use Exception\TypeException;

class SortedLinkedList {
    private int $position = 0;

    /**
     * @param ListType $type The type of values in the list
     * @param array<string|int> $list The initial list of values
     * @throws TypeException
     */
    public function __construct(private ListType $type, private array $list = []) {
        foreach ($list as $value) {
            $this->validateType($value);
        }

        if (!empty($this->list)) {
            sort($this->list);
        }

        $this->position = 0;
    }

    /**
     * Insert a value into the list to the correct place in terms of the order of the list.
     * @param string|int $value The value to insert
     * @throws TypeException
     */
    public function insert(string|int $value): void {
        $this->validateType($value);

        $insertIndex = 0;
        foreach ($this->list as $index => $item) {
            if ($value < $item) {
                break;
            }
            $insertIndex = $index + 1;
        }

        array_splice($this->list, $insertIndex, 0, [$value]);
    }

    public function removeAll(string|int $value): void {
        $this->list = array_filter($this->list, fn($item) => $item !== $value);
    }

    public function removeByIndex(int $index): void {
        \array_splice($this->list, $index, 1);
    }

    public function contains(string|int $value): bool {
        return \in_array($value, $this->list, true);
    }

    public function size(): int {
        return count($this->list);
    }

    public function isEmpty(): bool {
        return empty($this->list);
    }

    public function getType(): ListType {
        return $this->type;
    }

    public function __toString(): string {
        return 'SortedLinkedList([' . implode(', ', $this->list) . '])';
    }

    public function currentValue(): string|int {
        return $this->list[$this->position];
    }

    public function currentPosition(): int {
        return $this->position;
    }

    public function next(): void {
        $this->position++;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    private function validateType(mixed $value): void {
        if (
            $this->type === ListType::STRING && !is_string($value)
            || $this->type === ListType::INTEGER && !is_integer($value)
        ) {
            throw new TypeException("Cannot insert " . \gettype($value) . " into a list of " . $this->type->value);
        }
    }
} 
