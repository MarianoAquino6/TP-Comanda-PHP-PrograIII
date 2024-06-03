<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

//Importaciones
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

//AUTOLOAD
require __DIR__ . '/../vendor/autoload.php';

//REQUIRES
require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';
require_once './controllers/usuarioController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
// Instantiate App
$app = AppFactory::create();
// Set base path
$app->setBasePath('/TP-Comanda-PHP-PrograIII/app');
// Add error middleware
$app->addErrorMiddleware(true, true, true);
// Add parse body
$app->addBodyParsingMiddleware();
//Zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \UsuarioController::class . ':ObtenerTodosLosUsuarios');
    $group->get('/{usuario}', \UsuarioController::class . ':ObtenerUsuario');
    $group->post('[/]', \UsuarioController::class . ':RegistrarUsuario');
    $group->put('[/]', \UsuarioController::class . ':ModificarUsuario');
    $group->delete('[/]', \UsuarioController::class . ':BorrarUsuario');
});

$app->group('/productos', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \ProductoController::class . ':ObtenerTodosLosProductos');
    $group->get('/{producto}', \ProductoController::class . ':ObtenerProducto');
    $group->post('[/]', \ProductoController::class . ':RegistrarProducto');
    $group->put('[/]', \ProductoController::class . ':ActualizarPrecioProducto');
    $group->delete('[/]', \ProductoController::class . ':BorrarProducto');
});

$app->group('/mesas', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \MesaController::class . ':ObtenerTodasLasMesas');
    $group->get('/{mesa}', \MesaController::class . ':ObtenerMesa');
    $group->post('[/]', \MesaController::class . ':RegistrarMesa');
    $group->put('[/]', \MesaController::class . ':ActualizarEstadoMesa');
    $group->delete('[/]', \MesaController::class . ':BorrarMesa');
});

$app->group('/pedido', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \PedidoController::class . ':ObtenerTodosLosPedidos');
    $group->get('/{pedido}', \PedidoController::class . ':ObtenerPedido');
    $group->post('[/]', \PedidoController::class . ':RegistrarPedido');
    $group->put('[/]', \PedidoController::class . ':ActualizarEstadoPedido');
    $group->delete('[/]', \PedidoController::class . ':BorrarPedido');
});

$app->get('[/]', function (Request $request, Response $response) 
{    
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();