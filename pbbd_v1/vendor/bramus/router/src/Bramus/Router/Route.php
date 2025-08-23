<?php

namespace Bramus\Router;

class Route
{
    private $pattern;
    private $fn;

    public function __construct($pattern, $fn)
    {
        $this->pattern = $pattern;
        $this->fn = $fn;
    }

    public function matches($uri)
    {
        $pattern = preg_replace('/\/\{([a-zA-Z0-9_]+)\}/', '/(?P<$1>[a-zA-Z0-9_]+)', $this->pattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            $this->params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }

    public function dispatch()
    {
        call_user_func_array($this->fn, $this->params);
    }
}
