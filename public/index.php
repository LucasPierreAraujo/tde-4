<?php

require __DIR__ . '/../vendor/autoload.php';

use App\ProdutoController;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$controller = new ProdutoController();

$app->get('/produtos',       [$controller, 'listar']);
$app->get('/produtos/{id}',  [$controller, 'buscar']);
$app->post('/produtos',      [$controller, 'criar']);
$app->put('/produtos/{id}',  [$controller, 'atualizar']);
$app->delete('/produtos/{id}', [$controller, 'deletar']);

$app->run();
