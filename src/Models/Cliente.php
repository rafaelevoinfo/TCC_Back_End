<?php
namespace Models;

class Cliente extends Modelo
{
    public $cpf;
    public $nome;
    public $endereco;
    public $estado;
    public $municipio;
    public $telefone;
    public $email;
    public $senha;

    //case sensitive aqui, se ficar diferente dos fields da classe nao vai funcionar o fetchObject
    const SQL = 'SELECT cliente.cpf,
                        cliente.nome,
                        cliente.endereco,
                        cliente.estado,
                        cliente.municipio,
                        cliente.telefone,
                        cliente.email,
                        cliente.senha
                 FROM cliente';
    const SQL_LIMIT = ' limit 50';
    const SQL_ORDER_BY = ' order by nome ';

    private function ppvCopiarDados($cliente)
    {
        $this->cpf = $cliente->cpf;
        $this->nome = $cliente->nome;
        $this->endereco = $cliente->endereco;
        $this->estado = $cliente->estado;
        $this->municipio = $cliente->municipio;
        $this->telefone = $cliente->telefone;
        $this->email = $cliente->email;
        $this->senha = $cliente->senha;
    }

    private function ppvBuscarCarregar($sql)
    {
        $vaStatement = $this->app->db->query($sql);
        $this->cpf = '';
        if ($vaCliente = $vaStatement->fetchObject('\Models\Cliente', array($this->app))) {
            $this->ppvCopiarDados($vaCliente);
        }
    }

    public function fpuCarregarPorCpf($cpf)
    {    
        $vaSql = self::SQL . ' where cliente.cpf = "' . $cpf . '"' .self::SQL_ORDER_BY. self::SQL_LIMIT;
        $this->ppvBuscarCarregar($vaSql);
        return ($this->cpf != '');
    }

    public function fpuCarregarPorNome($nome)
    {
        $vaSql = self::SQL . ' where Upper(cliente.nome) LIKE "' . strtoupper($nome) . '%"' .self::SQL_ORDER_BY. self::SQL_LIMIT;
        $this->ppvBuscarCarregar($vaSql);
        return ($this->cpf != '');
    }

    public function fpuCarregarPorEmail($email)
    {
        $vaSql = self::SQL . ' where UPPER(cliente.email) = "' . strtoupper($email) . '"' .self::SQL_ORDER_BY. self::SQL_LIMIT;
        $this->ppvBuscarCarregar($vaSql);
        return ($this->cpf != '');
    }

    public function fpuBuscarTodos()
    {
        $vaClientes = [];
        $vaStatement = $this->app->db->query(self::SQL .self::SQL_ORDER_BY. self::SQL_LIMIT);
        while ($vaCliente = $vaStatement->fetchObject('\Models\Cliente', array($this->app))) {
            $vaClientes[] = $vaCliente;
        }
        return $vaClientes;
    }

    public function fpuExcluir()
    {
        if ($this->app->db->exec('DELETE from cliente where cliente.cpf = "' . $this->cpf . '"') === 1) {
            return self::STATUS_OK;
        } else {
            return 'Não foi possível excluir esse cliente.';
        }
    }

    public function fpuSalvar()
    {
        $vaCliente = new \Models\Cliente($this->app);
        if ($vaCliente->fpuCarregarPorCpf($this->cpf)) {
            $vaSql = 'UPDATE `cliente` SET `CPF`=:cpf,`NOME`=:nome,`ENDERECO`=:endereco,`ESTADO`=:estado,
            `MUNICIPIO`=:municipio,`TELEFONE`=:telefone,`EMAIL`=:email,`SENHA`=:senha WHERE cliente.cpf = :cpf';
        } else {
            $vaSql = 'INSERT INTO `cliente`(`CPF`, `NOME`, `ENDERECO`, `ESTADO`, `MUNICIPIO`, `TELEFONE`, `EMAIL`, `SENHA`)
            VALUES (:cpf,:nome,:endereco, :estado,:municipio,:telefone,:email,:senha)';
        }
        
        if ((!$vaCliente->fpuCarregarPorEmail($this->email))||($vaCliente->cpf===$this->cpf)) {
            $vaStatement = $this->app->db->prepare($vaSql);
            $vaStatement->bindValue(':cpf', $this->cpf);
            $vaStatement->bindValue(':nome', $this->nome);
            $vaStatement->bindValue(':endereco', $this->endereco);
            $vaStatement->bindValue(':estado', $this->estado);
            $vaStatement->bindValue(':municipio', $this->municipio);
            $vaStatement->bindValue(':telefone', $this->telefone);
            $vaStatement->bindValue(':email', $this->email);
            $vaStatement->bindValue(':senha', $this->senha);
            if ($vaStatement->execute()) {
                return Modelo::STATUS_OK;
            } else {
                return 'Não foi possível salvar o cliente';
            }
        } else {
            return 'E-mail já cadastrado';
        }

    }
}
