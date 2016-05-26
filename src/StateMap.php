<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use ArrayAccess;
use SplObjectStorage;

final class StateMap implements ArrayAccess
{
    /**
     * Create an array based state map.
     *
     * Use this for int/string based states.
     *
     * @return StateMap
     */
    public static function createArrayMap(): self
    {
        return new self([]);
    }

    /**
     * Create an SplObjectStorage based state map.
     *
     * Use this for object based states.
     *
     * @return StateMap
     */
    public static function createObjectMap(): self
    {
        return new self(new SplObjectStorage());
    }

    /**
     * @return bool True if using array keys.
     */
    public function isUsingArrayKeys(): bool
    {
        return is_array($this->map);
    }

    /**
     * @return bool True if using object keys.
     */
    public function isUsingObjectKeys(): bool
    {
        return !$this->isUsingArrayKeys();
    }

    /**
     * @param mixed $key A state key to check for.
     *
     * @return bool True if the map contains the key.
     */
    public function contains($key): bool
    {
        return isset($this->map[$key]);
    }

    /**
     * @param mixed $offset A state key to get a value for.
     *
     * @return mixed The value associated with the given state.
     */
    public function offsetGet($offset)
    {
        assert($this->contains($offset));

        return $this->map[$offset];
    }

    /**
     * @param mixed $offset A state key to check if exists.
     *
     * @return bool True if the state exists.
     */
    public function offsetExists($offset)
    {
        return $this->contains($offset);
    }

    /**
     * @param mixed $offset A state key to set a value for.
     * @param mixed $value  The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $this->map[$offset] = $value;
    }

    /**
     * @param mixed $offset A state key to unset.
     */
    public function offsetUnset($offset)
    {
        assert($this->contains($offset));

        if ($this->isUsingArrayKeys()) {
            unset($this->map[$offset]);
        } else {
            $this->map->offsetUnset($offset);
        }
    }

    /**
     * @param mixed $map The map storage to use.
     */
    private function __construct($map)
    {
        $this->map = $map;
    }

    /**
     * @var map<mixed, mixed> The map of state key to mixed.
     */
    private $map;
}
