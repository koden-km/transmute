<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

final class Transitioner
{
    /**
     * @var StateMachine The state machine.
     */
    public $machine;

    /**
     * @var string|null The name of the next transition to perform, if any.
     */
    public $nextTransition;

    /**
     * @param StateMachine $machine The state machine in use.
     */
    public function __construct(StateMachine $machine)
    {
        $this->machine = $machine;
        $this->nextTransition = null;
    }

    /**
     * @param string $name      The name of the next transition to perform.
     * @param array  $arguments The arguments (unused).
     */
    public function __call(string $name, array $arguments)
    {
        $this->nextTransition = $name;
    }
}
