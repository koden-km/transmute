<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

interface StateGraphProvider
{
    public static function graph(): StateGraph;
}
