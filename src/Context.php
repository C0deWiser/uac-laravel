<?php

namespace Codewiser\UAC\Laravel;

/**
 * Class Context
 * @package Codewiser\UAC\Laravel
 *
 * @property $error
 */
class Context extends \Codewiser\UAC\AbstractContext
{
    protected function sessionSet($name, $value)
    {
        session([$name => $value]);
    }

    protected function sessionGet($name)
    {
        return session($name);
    }

    protected function sessionHas($name)
    {
        return session()->exists($name);
    }

    protected function sessionDel($name)
    {
        session()->forget($name);
    }
}
