<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use PHPUnit_Framework_TestCase;

class TransitionMapTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->intMap = TransitionMap::createArrayMap();
        $this->stringMap = TransitionMap::createArrayMap();
        $this->objectMap = TransitionMap::createObjectMap();
    }

    public function testIsUsingArrayKeys()
    {
        $this->assertTrue($this->intMap->isUsingArrayKeys());
        $this->assertTrue($this->stringMap->isUsingArrayKeys());
        $this->assertFalse($this->objectMap->isUsingArrayKeys());
    }

    public function testIsUsingObjectKeys()
    {
        $this->assertFalse($this->intMap->isUsingObjectKeys());
        $this->assertFalse($this->stringMap->isUsingObjectKeys());
        $this->assertTrue($this->objectMap->isUsingObjectKeys());
    }

    public function testContains()
    {
        $this->assertFalse($this->intMap->contains(10));
        $this->assertFalse($this->stringMap->contains('foo'));
        $this->assertFalse($this->objectMap->contains(GameStatus::PENDING()));

        $this->intMap[10] = ['transition' => 20];
        $this->stringMap['foo'] = ['transition' => 'bar'];
        $this->objectMap[GameStatus::PENDING()] = ['transition' => GameStatus::LIVE()];

        $this->assertTrue($this->intMap->contains(10));
        $this->assertTrue($this->stringMap->contains('foo'));
        $this->assertTrue($this->objectMap->contains(GameStatus::PENDING()));
    }

    public function testArrayAccess()
    {
        $this->assertFalse($this->intMap->offsetExists(10));
        $this->assertFalse($this->stringMap->offsetExists('foo'));
        $this->assertFalse($this->objectMap->offsetExists(GameStatus::PENDING()));

        $this->intMap[10] = ['transition' => 20];
        $this->stringMap['foo'] = ['transition' => 'bar'];
        $this->objectMap[GameStatus::PENDING()] = ['transition' => GameStatus::LIVE()];

        $this->assertTrue($this->intMap->offsetExists(10));
        $this->assertTrue($this->stringMap->offsetExists('foo'));
        $this->assertTrue($this->objectMap->offsetExists(GameStatus::PENDING()));

        $this->intMap->offsetUnset(10);
        $this->stringMap->offsetUnset('foo');
        $this->objectMap->offsetUnset(GameStatus::PENDING());

        $this->assertFalse($this->intMap->offsetExists(15));
        $this->assertFalse($this->stringMap->offsetExists('bar'));
        $this->assertFalse($this->objectMap->offsetExists(GameStatus::FINISHING()));

        $intData = ['anything' => 350];
        $stringData = ['anything' => 'doom'];
        $objectData = ['anything' => GameStatus::FINISHED()];

        $this->intMap->offsetSet(300, $intData);
        $this->stringMap->offsetSet('spam', $stringData);
        $this->objectMap->offsetSet(GameStatus::FINISHING(), $objectData);

        $this->assertSame($intData, $this->intMap->offsetGet(300));
        $this->assertSame($stringData, $this->stringMap->offsetGet('spam'));
        $this->assertSame($objectData, $this->objectMap->offsetGet(GameStatus::FINISHING()));
    }

    public function testWildcardDefaults()
    {
        $this->assertSame('*', $this->intMap->wildcard());
        $this->assertSame('*', $this->stringMap->wildcard());
        $this->assertSame(StateGraphWildcard::instance(), $this->objectMap->wildcard());
    }

    public function testWildcardAsInt()
    {
        $this->intMap = TransitionMap::createArrayMap(-1);

        $this->assertSame(-1, $this->intMap->wildcard());
    }
}
