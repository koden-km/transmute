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

    public function testCallCreatesStateTransitionsWithObjectMap()
    {
        $this->subject->waiting(GameStatus::PENDING());
        $this->subject[GameStatus::PENDING()]->starting(GameStatus::LIVE());

        $graph = $this->subject->build();

        $this->assertTrue($graph->contains(StateGraphWildcard::instance()));
        $this->assertTrue($graph->contains(GameStatus::PENDING()));
    }

    public function testCallCreatesStateTransitionsWithArrayMap()
    {
        $this->subject = StateGraphBuilder::create(false);

        $this->subject->waiting('pending');
        $this->subject['pending']->starting('live');

        $graph = $this->subject->build();

        $this->assertTrue($graph->contains('*'));
        $this->assertTrue($graph->contains('pending'));
    }

    public function testCallAddsMoreStateTransitionsWithObjectMap()
    {
        $this->subject[GameStatus::PENDING()]->starting(GameStatus::LIVE());
        $this->subject[GameStatus::PENDING()]->cancelled(GameStatus::CANCELLING());

        $graph = $this->subject->build();

        $this->assertTrue($graph->contains(GameStatus::PENDING()));
    }

    public function testCallAddsMoreStateTransitionsWithArrayMap()
    {
        $this->subject = StateGraphBuilder::create(false);

        $this->subject['pending']->starting('live');
        $this->subject['pending']->cancelled('cancelling');

        $graph = $this->subject->build();

        $this->assertTrue($graph->contains('pending'));
    }

    public function testBuildWithObjectMap()
    {
        $this->subject->someTransition(GameStatus::PENDING());
        $this->subject[GameStatus::PENDING()]->starting(GameStatus::LIVE());

        $graph = $this->subject->build();

        $this->assertTrue($graph->contains(StateGraphWildcard::instance()));
        $this->assertTrue($graph->contains(GameStatus::PENDING()));

        // Call build again, this time it should be empty.
        $graph = $this->subject->build();

        $this->assertFalse($graph->contains(StateGraphWildcard::instance()));
        $this->assertFalse($graph->contains(GameStatus::PENDING()));
    }

    public function testBuildWithArrayMap()
    {
        $this->subject = StateGraphBuilder::create(false);

        $this->subject->someTransition('pending');
        $this->subject['pending']->starting('live');

        $graph = $this->subject->build();

        $this->assertTrue($graph->contains('*'));
        $this->assertTrue($graph->contains('pending'));

        // Call build again, this time it should be empty.
        $graph = $this->subject->build();

        $this->assertFalse($graph->contains('*'));
        $this->assertFalse($graph->contains('pending'));
    }
}
