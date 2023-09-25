<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/MesaMiddleware.php';
require_once './middlewares/PedidosMiddleware.php';
require_once './middlewares/EmpleadoMiddleware.php';

require_once './controllers/jwtController.php';
require_once './controllers/ClienteController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/PlatoController.php';
require_once './controllers/EjerciciosController.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$app = AppFactory::create();

$app->setBasePath('/TP-ProgramacionIII/app');
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->group('/cliente', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ClienteController::class . ':TraerTodo');
    $group->post('[/]', \ClienteController::class . ':CargarUno')
     ->add(\MesaMiddleware::class . ':MesaLibre')
     ->add(\MesaMiddleware::class . ':MesaExistente')
     ->add(\MesaMiddleware::class . ':MesasVacias');
});

$app->group('/empleado', function (RouteCollectorProxy $group) {
    $group->post('/login', \EmpleadoController::class . ':Login')
    ->add(\EmpleadoMiddleware::class . ':ValidarAcceso');
    $group->get('[/]', \EmpleadoController::class . ':TraerTodo');
    $group->post('[/]', \EmpleadoController::class . ':CargarUno');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodo');
    $group->post('[/]', \MesaController::class . ':CargarUno');
});

$app->group('/pedido', function (RouteCollectorProxy $group) {
    $group->post('/sacarFoto', \PedidoController::class . ':SacarFoto');
    $group->get('[/]', \PedidoController::class . ':TraerTodo');
    $group->post('[/]', \PedidoController::class . ':CargarUno')
     ->add(\EmpleadoMiddleware::class . ':EmpleadoEsDelTipoDePedido')
     ->add(\PedidosMiddleware::class . ':ExisteElPlato')
     ->add(\MesaMiddleware::class . ':MesaNecesitaAtencion')
     ->add(\MesaMiddleware::class . ':MesasParaAtender')
     ->add(\EmpleadoMiddleware::class . ':EmpleadoDisponible');
});

$app->group('/plato', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PlatoController::class . ':TraerTodo');
    $group->post('[/]', \PlatoController::class . ':CargarUno');
});

$app->group('/ejercicios', function (RouteCollectorProxy $group) {
    $group->post('/1', \PedidoController::class . ':CargarUno')
    ->add(\EmpleadoMiddleware::class . ':EmpleadoEsDelTipoDePedido')
    ->add(\PedidosMiddleware::class . ':ExisteElPlato')
    ->add(\MesaMiddleware::class . ':MesaNecesitaAtencion')
    ->add(\MesaMiddleware::class . ':MesasParaAtender')
    ->add(\EmpleadoMiddleware::class . ':EmpleadoDisponible');
    $group->post('/2', \PedidoController::class . ':SacarFoto');
    $group->get('/3', \EmpleadoController::class . ':TraerTodo');//
    $group->get('/4', \PedidoController::class . ':TraerUno');
    $group->get('/5', \PedidoController::class . ':TraerListaDeUnPedidoYTiempo');
    $group->get('/6', \EmpleadoController::class . ':ListarPendiestesYServir');
    $group->get('/7', \EmpleadoController::class . ':CambiaEstadoAMesas');
    $group->get('/8', \MesaController::class . ':TraerTodo');
    $group->get('/9', \EmpleadoController::class . ':PedirCuenta');
    $group->get('/10', \MesaController::class . ':Cerrar');
    $group->post('/11', \ClienteController::class . ':CompletarEncuesta');
    //$group->get('/12', \PedidoController::class . ':TraerListaDeUnPedidoYTiempo');
    //$group->get('/12', \PedidoController::class . ':TraerListaDeUnPedidoYTiempo');
    //$group->get('/14', \PedidoController::class . ':TraerListaDeUnPedidoYTiempo');
    //$group->get('/15', \PedidoController::class . ':TraerListaDeUnPedidoYTiempo');
    //$group->get('/16', \PedidoController::class . ':TraerListaDeUnPedidoYTiempo');
    $group->get('/17', \EjerciciosController::class . ':Ejer17');
    $group->get('/18', \EjerciciosController::class . ':Ejer18');
    $group->get('/19', \EjerciciosController::class . ':Ejer19');
    $group->get('/21', \EjerciciosController::class . ':Ejer21');
    $group->get('/22', \EjerciciosController::class . ':Ejer22');
});

$app->group('/jwt', function (RouteCollectorProxy $group) {
    $group->post('/crearToken', \jwtController::class . ':crearToken');
    $group->get('/devolverPayLoad', \jwtController::class . ':devolverPayLoad');
    $group->get('/devolverDatos', \jwtController::class . ':devolverDatos');
    $group->get('/verificarToken', \jwtController::class . ':verificarToken');
});
$app->get('[/]', function (Request $request, Response $response) {
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP - TP"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
