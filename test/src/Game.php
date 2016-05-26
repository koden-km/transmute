<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

/**
 * An example class to have state operated on.
 */
final class Game
{
    public function __construct()
    {
        $this->state = GameStatus::CREATED();
        $this->value = 'default';
    }

    public function currentState(): GameStatus
    {
        return $this->state;
    }

    public function setCurrentState(GameStatus $newState) 
    {
        $this->state = $newState;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function setValue(string $value) 
    {
        $this->value = $value;
    }

    private $state;
    private $value;
}
