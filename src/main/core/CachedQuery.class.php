<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: CachedQuery.class.php

declare(strict_types=1);

class pCachedQuery implements Iterator, Countable
{
    private int $_row_count = 0;
    private array $_db_objects = [];
    private int $position = 0;
    private int $fetchPosition = 0;
    private string $_query = '';

    public function __construct(iterable $db_objects = [], int $row_count = 0, string $query = '')
    {
        $this->_row_count = $row_count;
        $this->_db_objects = is_array($db_objects) ? $db_objects : iterator_to_array($db_objects);
        $this->_query = $query;
    }

    public function rowCount(): int
    {
        return $this->_row_count;
    }

    public function count(): int
    {
        return $this->_row_count;
    }

    public function fetchAll(): array
    {
        $array = [];
        foreach ($this->_db_objects as $object) {
            $array[] = (array) $object;
        }
        return $array;
    }

    public function fetchObject(): mixed
    {
        if (!isset($this->_db_objects[$this->fetchPosition])) {
            return false;
        }
        
        $object = $this->_db_objects[$this->fetchPosition];
        $this->fetchPosition++;
        return $object;
    }

    public function rewind(): void
    {
        $this->position = 0;
        $this->fetchPosition = 0;
    }

    public function current(): mixed
    {
        return $this->_db_objects[$this->position] ?? false;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->_db_objects[$this->position]);
    }
}

class pSet
{
    private array $_fields = [];

    public function __construct()
    {
        $this->_fields = [];
    }

    public function add(mixed $field): void
    {
        if (isset($field->name)) {
            $this->_fields[$field->name] = $field;
        } else {
            $this->_fields[] = $field;
        }
    }

    public function remove(mixed $field): void
    {
        if (isset($field->name) && isset($this->_fields[$field->name])) {
            unset($this->_fields[$field->name]);
        }
    }

    public function get(): array
    {
        return $this->_fields;
    }
}