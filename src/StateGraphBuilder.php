<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use ArrayAccess;

final class StateGraphBuilder implements ArrayAccess
{
    /**
     * Create the state graph builder.
     *
     * @param bool $useObjectKeys True to use object keys for states.
     *
     * @return StateGraphBuilder
     */
    public static function create(bool $useObjectKeys): self
    {
        return new self($useObjectKeys);
    }

    /**
     * @param mixed $offset A state to setup transitions for.
     *
     * @return StateGraphBuilder
     */
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

    /**
     * Setup a state transition that can be performed.
     *
     * @param string       $name      The name of the transition that can be performed.
     * @param array<mixed> $arguments The first argument is the state after transition, any remaining arguments are ignored.
     *
     * @return StateGraphBuilder
     */
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
        $this->graph[$this->currentState] = $transitions;

        return $this;
    }

    /**
     * Build a StateGraph from the current builder graph.
     *
     * @return StateGraph
     */
    public function build(): StateGraph
    {
        try {
            return new StateGraph($this->graph);
        } finally { // @codeCoverageIgnore
            $this->reset($this->graph->isUsingObjectKeys());
        }
    }

    /**
     * Private constructor.
     *
     * Use the create() factory method.
     *
     * @param bool $useObjectKeys True to use object keys for states.
     */
    private function __construct(bool $useObjectKeys)
    {
        $this->reset($useObjectKeys);
    }

    /**
     * Reset the graph builder.
     *
     * @param bool $useObjectKeys True to use object keys for states.
     */
    private function reset(bool $useObjectKeys)
    {
        if ($useObjectKeys) {
            $this->graph = TransitionMap::createObjectMap();
        } else {
            $this->graph = TransitionMap::createArrayMap();
        }
        $this->currentState = $this->graph->wildcard();
    }

    /**
     * @var TransitionMap The state graph being created.
     */
    private $graph;

    /**
     * @var mixed The current transition state.
     */
    private $currentState;
}
