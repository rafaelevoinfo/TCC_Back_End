<?php
namespace Controllers;

class ControleUsuario extends ControleBasicoCrud
{
    //Pega o json recebido da aplicação cliente e alimenta o objeto Usuario
    protected function criarObjeto($postData){
        $vaUsuario = new \Models\Usuario($this->app);
        foreach ($postData as $key => $value) {
            $vaUsuario->$key = $value;
        }
        //remove pontos e traços do CPF para armazenarmos somente numeros
        $vaUsuario->cpf = str_replace('.','',$vaUsuario->cpf);
        $vaUsuario->cpf = str_replace('-','',$vaUsuario->cpf);
        return $vaUsuario;
    }

    public function salvar($request)
    {       
        //Pega o conteudo do corpo da requisição POST ou PUT 
        $vaPostData = $request->getParsedBody();
        if (isset($vaPostData)) {
            $vaUsuario = $this->criarObjeto($vaPostData);
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
        $filtro = $vaUsuario->removerMascaraCpf($filtro);

        $vaUsuarios = [];
        //se nao for passado filtro nenhum, vai retornar todos
        if ((\is_numeric($filtro)) || ($filtro != '')) {
            $vaAchou = false;           
            //se é um numero entao é uma busca por CPF
            if (\is_numeric($filtro)) {
                $vaAchou = $vaUsuario->carregarPorCpf($filtro);
                if ($vaAchou){
                    //uso um array somente para manter o mesmo padrao de json de retorno, mesmo que contenha somente um registro
                    $vaUsuarios[] = $vaUsuario;
                }
            } else if ($filtro != '') {
                $vaUsuarios = $vaUsuario->buscarPorNome($filtro);                                
            }
            //converte uma classe para um json
            return \json_encode($vaUsuarios);            
        } else {
            $vaUsuarios = $vaUsuario->buscarTodos();
            return \json_encode($vaUsuarios);
        }
    }
}

?>