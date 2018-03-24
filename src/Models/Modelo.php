<?php
namespace Models;

//** Classe base para todos os objetos de negocio */
class Modelo
{
    protected $app;
    
    const STATUS_OK = 'OK';

    public function __construct($app)
    {
        $this->app = $app;
    }
}

?>