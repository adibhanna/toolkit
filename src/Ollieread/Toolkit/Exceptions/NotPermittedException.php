<?php namespace Ollieread\Toolkit\Exceptions; 

class NotPermittedException extends \Exception
{

    protected $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

} 