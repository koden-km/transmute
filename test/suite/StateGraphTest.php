<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use PHPUnit_Framework_TestCase;
// use SplObjectStorage;

class StateGraphTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->graph = TransitionMap::createArrayMap();

        $this->subject = new StateGraph(
            $this->graph
        );
    }

    public function testPlaceholder()
    {
        $this->markTestIncomplete();
    }

    // public function testContains()
    // {
    //     $this->assertFalse(
    //         $this->subject->contains('foo')
    //     );

    //     $this->graph['foo'] = 'bar';

    //     $this->assertTrue(
    //         $this->subject->contains('foo')
    //     );
    // }
}
