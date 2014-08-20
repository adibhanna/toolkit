<?php namespace Ollieread\Toolkit
;
use Illuminate\Support\Facades\Config;

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

        $file = app_path() . '/' . Config::get('toolkit::routes.directory') . '/' . $file;

        parent::group($attributes, function() use($file)
        {
            require $file;

        });
    }

    /**
     * Override the route resource, this prevents it from being used while not in
     * debug, largely because it's lazy and I don't want it being used on my
     * applications!
     *
     * @param string $name
     * @param string $controller
     * @param array  $options
     */
    public function resource($name, $controller, array $options = array())
    {
        if(Config::get('app.debug')) {
            parent::resource($name, $controller, $options);
        }
    }

    /**
     * Override the route controller, this prevents it from being used while not in
     * debug, largely because it's lazy and I don't want it being used on my
     * applications!
     *
     * @param string $uri
     * @param string $controller
     * @param array  $names
     */
    public function controller($uri, $controller, $names = array())
    {
        if(Config::get('app.debug')) {
            parent::resource($uri, $controller, $names);
        }
    }

}