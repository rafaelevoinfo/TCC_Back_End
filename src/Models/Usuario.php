<?php
namespace Models;

class UsuarioSimples extends Modelo
{
    public $cpf;
    public $nome;    
}

class Usuario extends UsuarioSimples
{
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

    private function copiarDados($usuario)
    {
        $this->cpf = $usuario->cpf;
        $this->nome = $usuario->nome;
        $this->endereco = $usuario->endereco;
        $this->estado = $usuario->estado;
        $this->municipio = $usuario->municipio;
        $this->telefone = $usuario->telefone;
        $this->email = $usuario->email;
        $this->senha = $usuario->senha;
    }

    private function buscarCarregar($sql, $classe)
    {
        $vaStatement = $this->app->db->query($sql);
        $this->cpf = '';
        if ($vaUsuario = $vaStatement->fetchObject($classe, array($this->app))) {
            $this->copiarDados($vaUsuario);
        }
    }

    public function validarSenha($senha){
        return $senha===$this->senha;
    }

    public function carregarPorCpf($cpf)
    {
        $vaSql = self::SQL . ' where cliente.cpf = "' . $cpf . '"' . self::SQL_ORDER_BY . self::SQL_LIMIT;
        $this->buscarCarregar($vaSql, '\Models\Usuario');
        return ($this->cpf != '');
    }

    public function buscarPorNome($nome)
    {
        $vaUsuarios = [];
        $vaSql = 'SELECT cliente.cpf,
                         cliente.nome
                  FROM cliente
                  where Upper(cliente.nome) LIKE "' . strtoupper($nome) . '%"' .
        self::SQL_ORDER_BY . self::SQL_LIMIT;

        $vaStatement = $this->app->db->query($vaSql);
        while ($vaUsuario = $vaStatement->fetchObject('\Models\UsuarioSimples', array($this->app))) {
            $vaUsuarios[] = $vaUsuario;
        }
        return $vaUsuarios;
    }

    public function carregarPorEmail($email)
    {
        $vaSql = self::SQL . ' where UPPER(cliente.email) = "' . strtoupper($email) . '"' . self::SQL_ORDER_BY . self::SQL_LIMIT;
        $this->buscarCarregar($vaSql, '\Models\Usuario');
        return ($this->cpf != '');
    }

    public function buscarTodos()
    {
        $vaUsuarios = [];
        $vaStatement = $this->app->db->query('SELECT cliente.cpf,
                                                    cliente.nome
                                              FROM cliente ' .
            self::SQL_ORDER_BY . self::SQL_LIMIT);
        while ($vaUsuario = $vaStatement->fetchObject('\Models\UsuarioSimples', array($this->app))) {
            $vaUsuarios[] = $vaUsuario;
        }
        return $vaUsuarios;
    }

    public function excluir()
    {
        if ($this->nome != 'admin') {
            if ($this->app->db->exec('DELETE from cliente where cliente.cpf = "' . $this->cpf . '"') === 1) {
                return self::STATUS_OK;
            } else {
                return 'Não foi possível excluir esse cliente.';
            }
        } else {
            return 'Não é permitido excluir o admin';
        }
    }

    public function salvar()
    {
        $vaUsuario = new \Models\Usuario($this->app);
        if ($vaUsuario->carregarPorCpf($this->cpf)) {
            $vaSql = 'UPDATE `cliente` SET `CPF`=:cpf,`NOME`=:nome,`ENDERECO`=:endereco,`ESTADO`=:estado,
            `MUNICIPIO`=:municipio,`TELEFONE`=:telefone,`EMAIL`=:email,`SENHA`=:senha WHERE cliente.cpf = :cpf';
        } else {
            $vaSql = 'INSERT INTO `cliente`(`CPF`, `NOME`, `ENDERECO`, `ESTADO`, `MUNICIPIO`, `TELEFONE`, `EMAIL`, `SENHA`)
            VALUES (:cpf,:nome,:endereco, :estado,:municipio,:telefone,:email,:senha)';
        }

        if ((!$vaUsuario->carregarPorEmail($this->email)) || ($vaUsuario->cpf === $this->cpf)) {
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
