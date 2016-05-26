<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

// use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class StateLogicTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->machine = new StateMachine(
            GameStatus::graph(),
            true,
            Game::class
        );

        $this->transitioner = new Transitioner(
            $this->machine
        );

        $this->subject = new GameLiveLogic();

        $this->game = new Game();
        $this->game->setCurrentState(GameStatus::LIVE());
    }

    public function testUpdate()
    {
        $this->subject->update($this->transitioner, $this->game);

        $this->assertSame(
            'update: current=LIVE',
            $this->game->value()
        );
    }

    public function testEnter()
    {
        $game = new Game();
        $game->setCurrentState(GameStatus::LIVE());

        $this->subject->enter(
            $this->transitioner,
            GameStatus::PENDING(),
            $this->game
        );

        $this->assertSame(
            'enter: current=LIVE, previous=PENDING',
            $this->game->value()
        );
    }

    public function testLeave()
    {
        $this->subject->leave(
            $this->transitioner,
            GameStatus::FINISHING(),
            $this->game
        );

        $this->assertSame(
            'leave: current=LIVE, next=FINISHING',
            $this->game->value()
        );
    }
}
