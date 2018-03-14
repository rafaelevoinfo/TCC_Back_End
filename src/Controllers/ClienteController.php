<?php
namespace Controllers;

class ClienteController extends BaseController
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function fpuIncluirAlterar($request)
    {
        $vaCliente = new \Models\Cliente($this->app);
        $vaPostData = $request->getParsedBody();
        if (isset($vaPostData)) {
            foreach ($vaPostData as $key => $value) {
                $vaCliente->$key = $value;
            }
            $vaCliente->cpf = str_replace('.','',$vaCliente->cpf);
            $vaCliente->cpf = str_replace('-','',$vaCliente->cpf);
            $vaStatus = $vaCliente->fpuSalvar();
            return $this->fprRetornarStatus($vaStatus);
        }else{
            return $this->fprRetornarStatus('Os dados do cliente não foram informados.');
        }

       
    }

    public function fpuExcluir($cpf)
    {
        $vaCliente = new \Models\Cliente($this->app);
        if ($vaCliente->fpuCarregarPorCpf($cpf)) {
            $vaStatus = $vaCliente->fpuExcluir();
            return $this->fprRetornarStatus($vaStatus);
        } else {
            return $this->fprRetornarStatus('Cliente não encontrado');
        }
    }

    public function fpuBuscar($filtro)
    {
        $vaCliente = new \Models\Cliente($this->app);
        //Removendo . e - do CPF (se for um CPF)
        $filtro = str_replace('.','',$filtro);
        $filtro = str_replace('-','',$filtro);

        if ((\is_numeric($filtro)) || ($filtro != '')) {
            $vaAchou = false;
            if (\is_numeric($filtro)) {
                $vaAchou = $vaCliente->fpuCarregarPorCpf($filtro);
                if ($vaAchou){
                    $vaClientes[] = $vaCliente;
                }
            } else if ($filtro != '') {
                $vaClientes = $vaCliente->fpuCarregarPorNome($filtro);                                
            }
            
            return \json_encode($vaClientes);            
        } else {
            $vaClientes = $vaCliente->fpuBuscarTodos();
            return \json_encode($vaClientes);
        }
    }
}
