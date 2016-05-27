<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

// use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class StateMachineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // TODO: Ensure this system works with types: int, string and eloquent enumeration.

        $this->subject = new StateMachine(
            GameStatus::graph(),
            true,
            Game::class
        );

        $this->gameLogicLive = new GameLiveLogic();
        $this->gameLogicFinishing = new GameFinishingLogic();
        $this->gameLogicFinished = new GameFinishedLogic();

        $this->subject[GameStatus::LIVE()] = $this->gameLogicLive;
        $this->subject[GameStatus::FINISHING()] = $this->gameLogicFinishing;
        $this->subject[GameStatus::FINISHED()] = $this->gameLogicFinished;
    }

    public function testOffsetExists()
    {
        $this->assertTrue(
            $this->subject->offsetExists(GameStatus::LIVE())
        );
    }

    public function testOffsetGetMethodIsFluent()
    {
        $this->assertSame(
            $this->gameLogicLive,
            $this->subject->offsetGet(GameStatus::LIVE())
        );
    }

    public function testOffsetGetOperatorIsFluent()
    {
        $this->assertSame(
            $this->gameLogicLive,
            $this->subject[GameStatus::LIVE()]
        );
    }

    public function testOffsetUnset()
    {
        $this->subject->offsetUnset(GameStatus::LIVE());

        $this->assertFalse(
            $this->subject->offsetExists(GameStatus::LIVE())
        );
    }

    public function testValidate()
    {
        // TODO: verify that asserts don't fail? or should the method throw?

        // $this->subject->validate(
        //     GameStatus::CREATED(),
        //     GameStatus::PENDING(),
        //     GameStatus::REGISTERING(),
        //     GameStatus::DELAYED(),
        //     GameStatus::LIVE(),
        //     GameStatus::FINISHING(),
        //     GameStatus::FINISHED(),
        //     GameStatus::CANCELLING(),
        //     GameStatus::CANCELLED()
        // );

        $this->markTestIncomplete();
    }

    public function testUpdateWithNonTerminalState()
    {
        $game = new Game();
        $game->setState(GameStatus::FINISHING());

        $this->assertSame(GameStatus::FINISHING(), $game->state());

        $this->subject->update($game->state(), $game);

        $this->assertSame(GameStatus::FINISHING(), $game->state());
        $this->assertSame(
            [
                'update: current=FINISHING',
            ],
            $game->log()
        );
    }

    public function testUpdateWithTerminalState()
    {
        $game = new Game();
        $game->setState(GameStatus::FINISHING());
        // This flag will make it transition to a terminal state.
        $game->setCompleteFlag(true);

        $this->assertSame(GameStatus::FINISHING(), $game->state());

        $this->subject->update($game->state(), $game);

        $this->assertSame(GameStatus::FINISHED(), $game->state());
        $this->assertSame(
            [
                'update: current=FINISHING',
                'leave: current=FINISHING, next=FINISHED',
                'enter: current=FINISHED, previous=FINISHING',
            ],
            $game->log()
        );
    }
}
