<?php
namespace Controllers;

class AutenticacaoController extends BaseController
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
    public function autenticar()
    {
        if ((isset($_SERVER["PHP_AUTH_USER"])) && (isset($_SERVER["PHP_AUTH_PW"]))) {            
            $vaUsuario = new \Models\Usuario($this->app);
            if (($vaUsuario->carregarPorEmail($_SERVER["PHP_AUTH_USER"])) && ($vaUsuario->validarSenha($_SERVER["PHP_AUTH_PW"]))){
                return $this->retornarStatus(\Models\Modelo::STATUS_OK); 
            }else{
                return $this->retornarStatus('Usuário e/ou senha incorretos');
            }              
        } else {
            return $this->retornarStatus('Usuário e/ou senha não informados');
        }
    }
}
