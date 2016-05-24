<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use PHPUnit_Framework_TestCase;

class StateGraphBuilderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = StateGraphBuilder::create(true);
    }

    public function testOffsetGetMethodIsFluent()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->offsetGet(GameStatus::CREATED())
        );
    }

    public function testOffsetGetOperatorIsFluent()
    {
        $this->assertSame(
            $this->subject,
            $this->subject[GameStatus::CREATED()]
        );
    }

    public function testCallIsFluent()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->waiting(GameStatus::PENDING())
        );
    }

    public function testCallDoesCreateStateTransition()
    {
        $this->subject->waiting(GameStatus::PENDING());
        $this->subject[GameStatus::PENDING()]->starting(GameStatus::LIVE());

        $graph = $this->subject->build();

        $this->assertTrue($graph->contains(StateGraphWildcard::instance()));
        $this->assertTrue($graph->contains(GameStatus::PENDING()));

        // TODO: Due to SplObjectStorage, i can't use string or in keys for states.
        // Changing to a generic TransitionMap object.
        //
        // $this->assertTrue($graph->contains('starting'));
        // $this->assertTrue($graph->contains('bar'));
        // $this->assertTrue($graph->contains('jazz'));
    }
}
