<?php
namespace Controllers;

abstract class ControleBasicoCrud extends ControleBasico{
    public abstract function salvar($request);
    public abstract function excluir($cpf);
    public abstract function buscar($filtro);
    
}