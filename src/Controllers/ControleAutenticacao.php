<?php
namespace Controllers;

class ControleAutenticacao extends ControleBasico
{
    public function autenticar($usuario, $senha)
    {
        if ((isset($usuario)) && (isset($senha))) {            
            $vaUsuario = new \Models\Usuario($this->app);
            //valida o email e a senha com os dados do banco
            if (($vaUsuario->carregarPorEmail($usuario)) && ($vaUsuario->validarSenha($senha))){
                return $this->retornarStatus(\Models\Modelo::STATUS_OK); 
            }else{
                return $this->retornarStatus('Usuário e/ou senha incorretos');
            }              
        } else {
            return $this->retornarStatus('Usuário e/ou senha não informados');
        }
    }
}
