<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use PHPUnit_Framework_TestCase;

class TransitionerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->machine = new StateMachine(
            new StateGraph(TransitionMap::createArrayMap())
        );

        $this->subject = new Transitioner(
            $this->machine
        );
    }

    public function testConstruct()
    {
        $this->assertSame(
            $this->machine,
            $this->subject->machine
        );

        $this->assertNull($this->subject->nextTransition);
    }

    public function testCall()
    {
        $this->subject->foo();

        $this->assertSame(
            $this->machine,
            $this->subject->machine
        );

        $this->assertSame(
            'foo',
            $this->subject->nextTransition
        );
    }
}
