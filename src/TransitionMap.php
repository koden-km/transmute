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
     * @param int|string $wildcard The wildcard type for this map.
     *
     * @return TransitionMap
     */
    public static function createArrayMap($wildcard = '*'): self
    {
        return new self([], $wildcard);
    }

    /**
     * Create an SplObjectStorage based transition map.
     *
     * Use this for object based states.
     *
     * @param object $wildcard The wildcard type for this map.
     *
     * @return TransitionMap
     */
    public static function createObjectMap(object $wildcard = null): self
    {
        if (null === $wildcard) {
            $wildcard = StateGraphWildcard::instance();
        }

        return new self(new SplObjectStorage(), $wildcard);
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
        return isset($this->map[$key]);
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
        assert($this->contains($offset));

        if ($this->isUsingArrayKeys()) {
            unset($this->map[$offset]);
        } else {
            $this->map->offsetUnset($offset);
        }
    }

    /**
     * @return int|string|object The wildcard instance.
     */
    public function wildcard()
    {
        return $this->wildcard;
    }

    /**
     * @param mixed             $map      The map storage to use.
     * @param int|string|object $wildcard The wildcard instance.
     */
    private function __construct($map, $wildcard)
    {
        $this->map = $map;
        $this->wildcard = $wildcard;
    }

    /**
     * @var map<mixed, array<string, mixed>> The map of state key to array of name based transitions.
     */
    private $map;

    /**
     * @var int|string|object The wildcard instance.
     */
    private $wildcard;
}
