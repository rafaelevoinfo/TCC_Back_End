<?php 
namespace Controllers;

abstract class ControleBasico{
    protected $app;

    public function __construct($app){
        $this->app = $app;
    }

    protected function retornarStatus($status)
    {
        return \json_encode(array(
            'status' => $status,
        ));
    }
}