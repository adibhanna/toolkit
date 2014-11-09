<?php namespace Ollieread\Toolkit;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
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

    public function boot()
    {
        $this->package('ollieread/toolkit');
    }

    /**
     * Overrides the router so that we can use our custom methods.
     */
    protected function registerRouterOverride()
    {

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
        if(Config::get('toolkit::catch_validation', true)) {

            App::error(function (ValidationException $e) {
                // Here we check the referer so that we don't get 'unable to redirect to empty url' errors.
                $referer = Request::server('HTTP_REFERER');

                if (!empty($referer)) {
                    // Send the input and the errors
                    return Redirect::back()->withInput()->withErrors($e->getErrors());
                }

            });

        }
    }

}
