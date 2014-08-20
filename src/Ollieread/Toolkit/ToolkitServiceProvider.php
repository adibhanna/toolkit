<?php namespace Ollieread\Toolkit;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Ollieread\Toolkit\Exceptions\ValidationException;

class ToolkitServiceProvider extends ServiceProvider
{

	protected $defer = false;

    /**
     * Register the router & input override, along with the global
     * handlers.
     */
    public function register()
	{
        $this->registerRouterOverride();
        $this->registerInputOverride();
        $this->registerErrorHandlers();
	}

    /**
     * Overrides the router so that we can use our custom methods.
     */
    protected function registerRouterOverride()
    {
        $this->app['router'] = $this->app->share(function($app)
        {
            $router = new Router($app['events'], $app);
            if ($app['env'] == 'testing')
            {
                $router->disableFilters();
            }

            return $router;
        });
    }

    /**
     *
     */
    protected function registerInputOverride()
    {
        // You like men
        // YOU CAN READ MY MIND?
        // No
        // SHIT!
    }

    /**
     * This is just a basic error handler for ValidationException, saves having
     * to use try/catch blocks and just allows for simple redirection.
     *
     * I'll probably add something in to add other stuff to this such as flash
     * messages.
     */
    protected function registerErrorHandlers()
    {
        App::error(function(ValidationException $e)
        {
            $referer = Request::server('HTTP_REFERER');
            if(!empty($referer)) {
                return Redirect::back()->withInput()->withErrors($e->getErrors());
            }
        });
    }

}
