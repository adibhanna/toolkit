<?php namespace Ollieread\Toolkit;

class Router extends \Illuminate\Routing\Router
{

    /**
     * Adds a file method, basically simplifies the splitting up of routes into
     * individual files.
     *
     * @param       $prefix
     * @param       $file
     * @param array $attributes
     */
    public function file($prefix, $file, array $attributes)
    {
        $attributes['prefix'] = $prefix;

        $file = app_path() . '/routes/' . $file;

        parent::group($attributes, function() use($file)
        {
            require $file;

        });
    }

}