<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use PHPUnit_Framework_TestCase;

class StateGraphTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = GameStatus::graph();
    }

    public function testContains()
    {
        $this->assertTrue(
            $this->subject->contains(GameStatus::LIVE())
        );

        $this->assertFalse(
            $this->subject->contains(GameStatus::DELAYED())
        );
    }

    public function testIsInitialState()
    {
        $this->assertTrue(
            $this->subject->isInitialState(StateGraphWildcard::instance())
        );
    }

    public function testIsTerminalState()
    {
        $this->assertTrue(
            $this->subject->isTerminalState(GameStatus::FINISHED())
        );
    }

    public function testFindStateByTransition()
    {
        $this->assertSame(
            GameStatus::FINISHED(),
            $this->subject->findStateByTransition(GameStatus::FINISHING(), 'finalize')
        );
    }
}
