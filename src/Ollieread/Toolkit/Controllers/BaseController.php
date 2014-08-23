<?php namespace Ollieread\Toolkit\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{

    protected function share($name, $value)
    {
        View::share($name, $value);
    }

}