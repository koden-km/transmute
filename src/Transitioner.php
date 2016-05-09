<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

final class Transitioner
{
    /**
     * @var StateMachine The state machine.
     */
    public $machine;

    public function __construct(StateMachine $machine)
    {
        $this->machine = $machine;
    }

    /**
     * @param string $name      The name of the transition to use.
     * @param array  $arguments The arguments (unused).
     */
    public function __call(string $name, array $arguments)
    {
        $this->machine->transition($name);
    }
}
