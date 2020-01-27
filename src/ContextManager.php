<?php

namespace Codewiser\UAC\Laravel;

class ContextManager extends \Codewiser\UAC\ContextManager
{
    public function __get($name)
    {
        return session('oauth2'.$name);
    }
    public function __set($name, $value)
    {
        session(['oauth2'.$name => $value]);
    }
    public function __isset($name)
    {
        return session()->exists('oauth2'.$name);
    }
    public function __unset($name)
    {
        session()->forget('oauth2'.$name);
    }
}
