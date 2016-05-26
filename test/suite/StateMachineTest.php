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
            GameStatus::class
        );
    }

    public function testPlaceholder()
    {
        $this->markTestIncomplete();
    }
}
