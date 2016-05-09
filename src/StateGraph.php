<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

class StateGraph implements ArrayAccess
{
    public static function create(string $enumType = null): self {
        return new self($enumType);
    }

    // TODO: something like this?
    // private function canTransition($from, $to): bool {
    //     if (!$this->graph->offsetExists($from)) {
    //         return false;
    //     }
    //     if (!$this->graph->offsetExists($to)) {
    //         return false;
    //     }

    //     if ($this->transitions[$this->currentState][$this->transitionName][$newState];
    // }

    public function executor(): StateExecutor {
        return new StateExecutor($this);
    }

    public function offsetExists($offset) {
        return $this->transitions->offsetExists($offset);
    }

    public function offsetGet($offset) {
        $this->currentNode = $offset;
        return $this->transitions->offsetGet($offset);
    }

    public function offsetSet($offset, $value) {
        $this->currentNode = $offset;
        $this->transitions->offsetSet($offset, $value);
    }

    public function offsetUnset($offset) {
        $this->transitions->offsetUnset($offset);
    }

    public function __invoke($transitionName, $newState) {
        $this->transitions[$this->currentNode][$this->transitionName][$newState];
        return $this;
    }

    // TODO: A validation feature to ensure all enum values have transitions.
    public function enumerationFullyMapped(): bool {
        if (null === $this->enumType) {
            return true;
        }

        // TODO: get {$this->enumType} class constants and check if they all
        // have mapped transitions.

        return false;
    }

    private function __construct(string $enumType = null) {
        $this->transitions = SplObjectStorage();
        $this->enumType = $enumType;
        $this->currentNode = null;
    }

    /**
     * @var SplObjectStorage
     */
    private $transitions;

    /**
     * @var string|null The enumeration class name or null if none.
     */
    private $enumType;

    /**
     * @var mixed|null The current transition state, or null if none.
     */
    private $currentNode;
}
