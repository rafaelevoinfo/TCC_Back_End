<?php
namespace Controllers;

class ClienteController
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    private function fpvRetornarStatus($status)
    {
        return \json_encode(array(
            'status' => $status,
        ));
    }

    public function fpuIncluirAlterar($request)
    {
        $vaCliente = new \Models\Cliente($this->app);
        $vaPostData = $request->getParsedBody();
        if (isset($vaPostData)) {
            foreach ($vaPostData as $key => $value) {
                $vaCliente->$key = $value;
            }
            $vaStatus = $vaCliente->fpuSalvar();
            return $this->fpvRetornarStatus($vaStatus);
        }else{
            return $this->fpvRetornarStatus('Os dados do cliente não foram informados.');
        }

       
    }

    public function fpuExcluir($cpf)
    {
        $vaCliente = new \Models\Cliente($this->app);
        if ($vaCliente->fpuCarregarPorCpf($cpf)) {
            $vaStatus = $vaCliente->fpuExcluir();
            return $this->fpvRetornarStatus($vaStatus);
        } else {
            return $this->fpvRetornarStatus('Cliente não encontrado');
        }
    }

    public function fpuBuscar($filtro)
    {
        $vaCliente = new \Models\Cliente($this->app);
        if ((\is_numeric($filtro)) || ($filtro != '')) {
            $vaAchou = false;
            if (\is_numeric($filtro)) {
                $vaAchou = $vaCliente->fpuCarregarPorCpf($filtro);
            } else if ($filtro != '') {
                $vaAchou = $vaCliente->fpuCarregarPorNome($filtro);
            }

            if ($vaAchou) {
                return \json_encode($vaCliente);
            } else {
                return '';
            }
        } else {
            $vaClientes = $vaCliente->fpuBuscarTodos();
            return \json_encode($vaClientes);
        }
    }
}
