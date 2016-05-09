<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;
use SplObjectStorage;

final class StateGraphBuilder implements ArrayAccess
{
    public static function create(): self
    {
        return new self();
    }

    public function offsetGet($offset)
    {
        $this->currentState = $offset;

        return $this;
    }

    public function offsetExists($offset)
    {
        assert(false, 'not implemented'); // @codeCoverageIgnore
    }

    public function offsetSet($offset, $value)
    {
        assert(false, 'not implemented'); // @codeCoverageIgnore
    }

    public function offsetUnset($offset)
    {
        assert(false, 'not implemented'); // @codeCoverageIgnore
    }

    public function __call(string $name, array $arguments)
    {
        assert(!empty($arguments));

        if ($this->graph->contains($this->currentState)) {
            $transitions = $this->graph[$this->currentState];
        } else {
            $transitions = [];
        }

        assert(!isset($transitions[$name]));

        $transitions[$name] = $arguments[0];
        $this->transitions[$this->currentState] = $transitions;

        return $this;
    }

    public function build(): StateGraph
    {
        try {
            return new StateGraph($this->graph);
        } finally {
            $this->graph = new SplObjectStorage();
            $this->currentState = StateGraphWildcard::instance();
        }
    }

    private function __construct()
    {
        $this->graph = new SplObjectStorage();
        $this->currentState = StateGraphWildcard::instance();
    }

    /**
     * @var SplObjectStorage<object, array<string, object>> The state graph to use.
     */
    private $graph;

    /**
     * @var mixed|null The current transition state, or null if none.
     */
    private $currentState;
}
