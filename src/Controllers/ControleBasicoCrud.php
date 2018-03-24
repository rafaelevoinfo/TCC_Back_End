<?php
namespace Controllers;

/** Classe basica para todo controle que contenha operações CRUD */
abstract class ControleBasicoCrud extends ControleBasico{
    public abstract function salvar($request);
    public abstract function excluir($cpf);
    public abstract function buscar($filtro);
    protected abstract function criarObjeto($postData);
    
}

?>