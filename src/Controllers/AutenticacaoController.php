<?php
namespace Controllers;

class AutenticacaoController
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
    public function fpuAutenticar()
    {
        if ((isset($_SERVER["PHP_AUTH_USER"])) && (isset($_SERVER["PHP_AUTH_PW"]))) {
            $sql = 'select count(*) as QTDE
                from cliente
              where cliente.email = "' . $_SERVER["PHP_AUTH_USER"] . '" and
                    cliente.senha = "' . $_SERVER["PHP_AUTH_PW"] . '"';

            $vaRow = $this->app->db->query($sql)->fetch();
            if (isset($vaRow['QTDE'])){
                return json_encode(array('login'=>'ok'));
            }
        }

        return \json_encode(array('login'=>'erro'));
    }
}
