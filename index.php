<?php
use \Controllers\ControleAutenticacao;
use \Controllers\ControleUsuario;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
//****************** CONFIGURACOES DE DEBUG *************/
// $config['db']['host']   = 'localhost';
// $config['db']['user']   = 'root';
// $config['db']['pass']   = '';
// $config['db']['dbname'] = 'tcc';

//****************** CONFIGURACOES DE RELEASE *************/
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'u645693451_root';
$config['db']['pass'] = 'root04';
$config['db']['dbname'] = 'u645693451_tcc';

$app = new \Slim\App(['settings' => $config]);

//Configurando conexao com banco de dados
$container = $app->getContainer();
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

//******************FAZENDO OS ROTEAMENTOS*************************
//*********************** AUTENTICACAO ********************************** */
$app->get('/auth', function (Request $request, Response $response, array $args) {
    $vaController = new ControleAutenticacao($this);
    if (isset($_SERVER["PHP_AUTH_USER"]) && ($_SERVER["PHP_AUTH_PW"])) {
        return $vaController->autenticar($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_PW"]);
    } else {
        return $vaController->autenticar('','');
    }
});

//************************** Usuarios ********************************* */
$app->get('/usuarios', function (Request $request, Response $response, array $args) {
    $vaController = new ControleUsuario($this);
    return $vaController->buscar('');
});

$app->get('/usuarios/{cpf_ou_nome}', function (Request $request, Response $response, array $args) {
    $vaController = new ControleUsuario($this);
    return $vaController->buscar($args['cpf_ou_nome']);
});

$app->map(['PUT', 'POST'], '/usuarios', function (Request $request, Response $response, array $args) {
    $vaController = new ControleUsuario($this);
    return $vaController->salvar($request);
});

$app->delete('/usuarios/{cpf}', function (Request $request, Response $response, array $args) {
    $vaController = new ControleUsuario($this);
    return $vaController->excluir($args['cpf']);
});

$app->run();
?>