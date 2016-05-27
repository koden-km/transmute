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
        $this->log = [];
        $this->completeFlag = false;
    }

    public function state(): GameStatus
    {
        return $this->state;
    }

    public function setState(GameStatus $newState)
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
        $this->log[] = $value;
    }

    public function log(): array
    {
        return $this->log;
    }

    public function completeFlag(): bool
    {
        return $this->completeFlag;
    }

    public function setCompleteFlag(bool $completeFlag)
    {
        $this->completeFlag = $completeFlag;
    }

    private $state;
    private $value;
    private $log;
    private $completeFlag;
}
