<?php
namespace Controllers;

class AutenticacaoController extends BaseController
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
    public function fpuAutenticar()
    {
        if ((isset($_SERVER["PHP_AUTH_USER"])) && (isset($_SERVER["PHP_AUTH_PW"]))) {            
            $vaCliente = new \Models\Cliente($this->app);
            if (($vaCliente->fpuCarregarPorEmail($_SERVER["PHP_AUTH_USER"])) && ($vaCliente->senha === $_SERVER["PHP_AUTH_PW"])){
                return $this->fprRetornarStatus(\Models\Modelo::STATUS_OK); 
            }else{
                return $this->fprRetornarStatus('Usuário e/ou senha incorretos');
            }              
        } else {
            return $this->fprRetornarStatus('Usuário e/ou senha não informados');
        }
    }
}
