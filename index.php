<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Controllers\AutenticacaoController;
use \Controllers\ClienteController;


require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'tcc';

$app = new \Slim\App(['settings' => $config]);

/*$app->get('/index/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});*/

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
    $vaController = new AutenticacaoController($this);
    return $vaController->fpuAutenticar();
});

//************************** Clientes ********************************* */
$app->get('/index/clientes', function (Request $request, Response $response, array $args) {
    $vaController = new ClienteController($this);
    return $vaController->fpuBuscar('');
});

$app->get('/index/clientes/{cpf_ou_nome}', function (Request $request, Response $response, array $args) {
    $vaController = new ClienteController($this);
    return $vaController->fpuBuscar($args['cpf_ou_nome']);
});

$app->map(['PUT','POST'], '/index/clientes', function (Request $request, Response $response, array $args){
    $vaController = new ClienteController($this);
    return $vaController->fpuIncluirAlterar($request);
});

$app->delete('/index/clientes/{cpf}', function (Request $request, Response $response, array $args) {
    $vaController = new ClienteController($this);
    return $vaController->fpuExcluir($args['cpf']);
});

$app->run();