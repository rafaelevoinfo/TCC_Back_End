<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Controllers\ControleAutenticacao;
use \Controllers\ControleUsuario;


require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
//****************** CONFIGURACOES DE DEBUG *************/
$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'tcc';

//****************** CONFIGURACOES DE RELEASE *************/
// $config['db']['host']   = 'localhost';
// $config['db']['user']   = 'id5062092_root';
// $config['db']['pass']   = 'root04';
// $config['db']['dbname'] = 'id5062092_tcc';


$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
//*********************** AUTENTICACAO ********************************** */
$app->get('/index/auth', function (Request $request, Response $response, array $args) {
    $vaController = new ControleAutenticacao($this);
    return $vaController->autenticar($_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"]);
});

//************************** Usuarios ********************************* */
$app->get('/index/usuarios', function (Request $request, Response $response, array $args) {
    $vaController = new ControleUsuario($this);
    return $vaController->buscar('');
});

$app->get('/index/usuarios/{cpf_ou_nome}', function (Request $request, Response $response, array $args) {
    $vaController = new ControleUsuario($this);
    return $vaController->buscar($args['cpf_ou_nome']);
});

$app->map(['PUT','POST'], '/index/usuarios', function (Request $request, Response $response, array $args){
    $vaController = new ControleUsuario($this);
    return $vaController->salvar($request);
});

$app->delete('/index/usuarios/{cpf}', function (Request $request, Response $response, array $args) {
    $vaController = new ControleUsuario($this);
    return $vaController->excluir($args['cpf']);
});

$app->run();