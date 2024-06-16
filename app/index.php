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
require_once './controllers/mesaController.php';
require_once './controllers/pedidoController.php';
require_once './controllers/productoController.php';
require_once './controllers/reseñaController.php';
require_once './controllers/usuarioController.php';
require_once './middlewares/loggerMW.php';
require_once './middlewares/permisosMW.php';
require_once './middlewares/validadorMesasMW.php';
require_once './middlewares/validadorPedidosMW.php';
require_once './middlewares/validadorProductosMW.php';
require_once './middlewares/validadorUsuariosMW.php';
require_once './middlewares/validadorReseñasMW.php';

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
    // ->add(new PermisosMW(["ADMIN"]));

    $group->post('/registrar', \UsuarioController::class . ':RegistrarUsuario')
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::Registro))
    ->add(new PermisosMW(["ADMIN"]));

    $group->put('/modificar', \UsuarioController::class . ':ModificarUsuario')
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::Modificacion))
    ->add(new PermisosMW(["ADMIN"]));

    $group->delete('/borrar', \UsuarioController::class . ':BorrarUsuario')
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::Borrado));
    // ->add(new PermisosMW(["ADMIN"]));
});

$app->group('/mesas', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \MesaController::class . ':ObtenerTodasLasMesasConEstados');

    $group->get('/mas-usada', \MesaController::class . ':ObtenerMesaMasUsada')
    ->add(new PermisosMW(["ADMIN"]));

    $group->post('/registrar', \MesaController::class . ':RegistrarMesa')
    ->add(new ValidadorMesasMW(ModoValidacionMesas::Registro))
    ->add(new PermisosMW(["ADMIN"]));

    $group->put('/actualizar-estado', \MesaController::class . ':ActualizarEstadoMesa')
    ->add(new ValidadorMesasMW(ModoValidacionMesas::ActualizarEstado))
    ->add(new PermisosMW(["MOZO"]));

    $group->put('/cerrar', \MesaController::class . ':CerrarMesa')
    ->add(new ValidadorMesasMW(ModoValidacionMesas::BorradoCerrar))
    ->add(new PermisosMW(["ADMIN"]));

    $group->delete('/borrar', \MesaController::class . ':BorrarMesa')
    ->add(new ValidadorMesasMW(ModoValidacionMesas::BorradoCerrar))
    ->add(new PermisosMW(["ADMIN"]));
});

$app->group('/productos', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \ProductoController::class . ':ObtenerTodosLosProductos');

    $group->post('/registrar', \ProductoController::class . ':RegistrarProducto')
    ->add(new ValidadorProductosMW(ModoValidacionProductos::Registro))
    ->add(new PermisosMW(["ADMIN"]));

    $group->put('/actualizar-precio', \ProductoController::class . ':ActualizarPrecioProducto')
    ->add(new ValidadorProductosMW(ModoValidacionProductos::ActualizacionPrecio))
    ->add(new PermisosMW(["ADMIN"]));

    $group->delete('/borrar', \ProductoController::class . ':BorrarProducto')
    ->add(new ValidadorProductosMW(ModoValidacionProductos::Borrado))
    ->add(new PermisosMW(["ADMIN"]));
});

$app->group('/pedidos', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \PedidoController::class . ':ObtenerTodosPedidosConEstados');
    // ->add(new PermisosMW(["ADMIN"]));

    $group->get('/tiempo-restante', \PedidoController::class . ':ObtenerTiempoRestante');

    $group->post('/registrar', \PedidoController::class . ':RegistrarPedidoYActualizarMesa')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::Registro))
    ->add(new PermisosMW(["MOZO"]));

    $group->get('/obtener-pendientes', \PedidoController::class . ':ObtenerPedidosPendientesSegunSector')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::ObtenerRegistros));
    // ->add(new PermisosMW(["CERVECERO", "BARTENDER", "COCINERO"]));

    $group->get('/obtener-pedidos-tomados', \PedidoController::class . ':ObtenerPedidosTomadosMozo')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::ObtenerRegistros));
    // ->add(new PermisosMW(["MOZO"]));

    $group->get('/obtener-pedidos-listos', \PedidoController::class . ':ObtenerPedidosListosMozo')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::ObtenerRegistros));
    // ->add(new PermisosMW(["MOZO"]));

    $group->put('/tomar', \PedidoController::class . ':TomarPedido')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::TomarPedido))
    ->add(new PermisosMW(["CERVECERO", "BARTENDER", "COCINERO"]));

    $group->put('/terminar', \PedidoController::class . ':TerminarPedido')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::TerminarPedido))
    ->add(new PermisosMW(["CERVECERO", "BARTENDER", "COCINERO"]));

    $group->put('/cobrar', \PedidoController::class . ':CobrarMesa')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::Cobrar))
    ->add(new PermisosMW(["MOZO"]));
});

$app->group('/reseñas', function (RouteCollectorProxy $group) 
{
    $group->get('/top', \ReseñaController::class . ':ObtenerMejoresComentariosMesas');
    // ->add(new PermisosMW(["ADMIN"]));

    $group->post('/registrar', \ReseñaController::class . ':RegistrarReseña')
    ->add(new ValidadorReseñasMW(ModoValidacionReseñas::Registro));
});

$app->get('[/]', function (Request $request, Response $response) 
{    
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();