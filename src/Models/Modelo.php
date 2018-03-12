<?php
namespace Models;

class Modelo
{
    protected $app;
    
    const STATUS_OK = 'OK';

    public function __construct($app)
    {
        $this->app = $app;
    }
}
