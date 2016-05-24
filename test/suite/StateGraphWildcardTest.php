<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use PHPUnit_Framework_TestCase;

class StateGraphWildcardTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = StateGraphWildcard::instance();
    }

    public function testInstance()
    {
        $this->assertSame(
            StateGraphWildcard::instance(),
            $this->subject
        );
    }
}
