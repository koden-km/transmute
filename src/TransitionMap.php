<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use ArrayAccess;
use SplObjectStorage;

final class TransitionMap implements ArrayAccess
{
    /**
     * Create an array based transition map.
     *
     * Use this for int/string based states.
     *
     * @return TransitionMap
     */
    public static function createArrayMap(): self
    {
        return new self([]);
    }

    /**
     * Create an SplObjectStorage based transition map.
     *
     * Use this for object based states.
     *
     * @return TransitionMap
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
     * @param mixed $key A map key to check for.
     *
     * @return bool True if the map contains the key.
     */
    public function contains($key): bool
    {
        // if ($this->isUsingArrayKeys()) {
            return isset($this->map[$key]);
        // } else {
        //     return $this->map->contains($key);
        // }
    }

    /**
     * @param mixed $offset A transition state.
     *
     * @return mixed The transitions of the given state.
     */
    public function offsetGet($offset)
    {
        assert($this->contains($offset));

        return $this->map[$offset];
    }

    /**
     * @param mixed $offset A transition state.
     *
     * @return bool True if the state exists.
     */
    public function offsetExists($offset)
    {
        return $this->contains($offset);
    }

    /**
     * @param mixed $offset The state to transition from.
     * @param mixed $value  The state after transition.
     */
    public function offsetSet($offset, $value)
    {
        $this->map[$offset] = $value;
    }

    /**
     * @param mixed $offset A transition state.
     */
    public function offsetUnset($offset)
    {
        $this->map->offsetUnset($offset);
    }

    /**
     * @param mixed The map storage to use.
     */
    private function __construct($map)
    {
        $this->map = $map;
    }

    /**
     * @var array<mixed, array<string, mixed>> The map of key to array of name based transitions.
     */
    private $map;
}
