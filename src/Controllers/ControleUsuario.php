<?php
namespace Controllers;

class ControleUsuario extends ControleBasicoCrud
{
    private function criarUsuario($postData){
        $vaUsuario = new \Models\Usuario($this->app);
        foreach ($postData as $key => $value) {
            $vaUsuario->$key = $value;
        }
        $vaUsuario->cpf = str_replace('.','',$vaUsuario->cpf);
        $vaUsuario->cpf = str_replace('-','',$vaUsuario->cpf);
        return $vaUsuario;
    }

    public function salvar($request)
    {        
        $vaPostData = $request->getParsedBody();
        if (isset($vaPostData)) {
            $vaUsuario = $this->criarUsuario($vaPostData);
            $vaStatus = $vaUsuario->salvar();
            return $this->retornarStatus($vaStatus);
        }else{
            return $this->retornarStatus('Os dados do usuário não foram informados.');
        }

       
    }

    public function excluir($cpf)
    {
        $vaUsuario = new \Models\Usuario($this->app);
        if ($vaUsuario->carregarPorCpf($cpf)) {
            $vaStatus = $vaUsuario->excluir();
            return $this->retornarStatus($vaStatus);
        } else {
            return $this->retornarStatus('Usuario não encontrado');
        }
    }

    public function buscar($filtro)
    {
        $vaUsuario = new \Models\Usuario($this->app);
        //Removendo . e - do CPF (se for um CPF)
        $filtro = str_replace('.','',$filtro);
        $filtro = str_replace('-','',$filtro);

        if ((\is_numeric($filtro)) || ($filtro != '')) {
            $vaAchou = false;
            if (\is_numeric($filtro)) {
                $vaAchou = $vaUsuario->carregarPorCpf($filtro);
                if ($vaAchou){
                    $vaUsuarios[] = $vaUsuario;
                }
            } else if ($filtro != '') {
                $vaUsuarios = $vaUsuario->buscarPorNome($filtro);                                
            }
            
            return \json_encode($vaUsuarios);            
        } else {
            $vaUsuarios = $vaUsuario->buscarTodos();
            return \json_encode($vaUsuarios);
        }
    }
}
