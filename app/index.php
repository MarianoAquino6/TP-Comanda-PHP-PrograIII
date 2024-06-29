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
require_once './middlewares/JWTMW.php';
require_once './middlewares/auditoriaMW.php';

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
    //////////////////////////////////////////// GET /////////////////////////////////////////////////

    $group->get('[/]', \UsuarioController::class . ':ObtenerTodosLosUsuarios')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/logs-usuario', \UsuarioController::class . ':ObtenerLogsDeUsuario')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::LogsUsuario))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/pdf', \UsuarioController::class . ':ObtenerPDFUsuarios')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    $group->post('/registrar', \UsuarioController::class . ':RegistrarUsuario')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::Registro))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->post('/login', \UsuarioController::class . ':Login')
    ->add(new LoggerMW)
    ->add(new JWTMW(ModoJWT::CrearToken))
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::Login));

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

    $group->put('/modificar-username', \UsuarioController::class . ':ModificarUsername')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::ModificacionUsername))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/modificar-pass', \UsuarioController::class . ':ModificarPass')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::ModificacionPass))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/modificar-sector', \UsuarioController::class . ':ModificarSector')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::ModificacionSector))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/baja', \UsuarioController::class . ':DarDeBaja')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::BajaReactivacion))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/reactivar', \UsuarioController::class . ':Reactivar')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::BajaReactivacion))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// DELETE /////////////////////////////////////////////////

    $group->delete('/borrar', \UsuarioController::class . ':BorrarUsuario')
    ->add(new AuditoriaMW())
    ->add(new ValidadorUsuariosMW(ModoValidacionUsuarios::Borrado))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));
});

$app->group('/mesas', function (RouteCollectorProxy $group) 
{
    //////////////////////////////////////////// GET /////////////////////////////////////////////////

    $group->get('[/]', \MesaController::class . ':ObtenerTodasLasMesasConEstados')
    ->add(new AuditoriaMW())
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/mas-usada', \MesaController::class . ':ObtenerMesaMasUsada')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/menos-usada', \MesaController::class . ':ObtenerMesaMenosUsada')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/ordenar-segun-importe-menor-mayor', \MesaController::class . ':ObtenerMesasOrdenadasPorImporteMenorMayor')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/mayor-facturacion', \MesaController::class . ':ObtenerMesaMayorFacturacion')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/menor-facturacion', \MesaController::class . ':ObtenerMesaMenorFacturacion')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/mayor-importe', \MesaController::class . ':ObtenerMesaMayorImporte')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/menor-importe', \MesaController::class . ':ObtenerMesaMenorImporte')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/facturacion-fechas', \MesaController::class . ':ObtenerFacturacionEnPeriodo')
    ->add(new AuditoriaMW())
    ->add(new ValidadorMesasMW(ModoValidacionMesas::FacturacionPeriodo))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/mejores-comentarios', \MesaController::class . ':ObtenerMejoresComentarios')
    ->add(new AuditoriaMW())
    ->add(new ValidadorMesasMW(ModoValidacionMesas::Comentarios))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/peores-comentarios', \MesaController::class . ':ObtenerPeoresComentarios')
    ->add(new AuditoriaMW())
    ->add(new ValidadorMesasMW(ModoValidacionMesas::Comentarios))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    $group->post('/registrar', \MesaController::class . ':RegistrarMesa')
    ->add(new AuditoriaMW())
    ->add(new ValidadorMesasMW(ModoValidacionMesas::Registro))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

    $group->put('/actualizar-estado', \MesaController::class . ':ActualizarEstadoMesa')
    ->add(new AuditoriaMW())
    ->add(new ValidadorMesasMW(ModoValidacionMesas::ActualizarEstado))
    ->add(new PermisosMW(["MOZO", "ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/cerrar', \MesaController::class . ':CerrarMesa')
    ->add(new AuditoriaMW())
    ->add(new ValidadorMesasMW(ModoValidacionMesas::BorradoCerrar))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// DELETE /////////////////////////////////////////////////

    $group->delete('/borrar', \MesaController::class . ':BorrarMesa')
    ->add(new AuditoriaMW())
    ->add(new ValidadorMesasMW(ModoValidacionMesas::BorradoCerrar))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));
});

$app->group('/productos', function (RouteCollectorProxy $group) 
{
    //////////////////////////////////////////// GET /////////////////////////////////////////////////
    $group->get('[/]', \ProductoController::class . ':ObtenerTodosLosProductos')
    ->add(new AuditoriaMW())
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/csv', \ProductoController::class . ':ObtenerTodosLosProductosCSV')
    ->add(new AuditoriaMW())
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    $group->post('/registrar', \ProductoController::class . ':RegistrarProducto')
    ->add(new AuditoriaMW())
    ->add(new ValidadorProductosMW(ModoValidacionProductos::Registro))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));
    
    $group->post('/importar', \ProductoController::class . ':ImportarProductos')
    ->add(new AuditoriaMW())
    ->add(new ValidadorProductosMW(ModoValidacionProductos::Importar))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

    $group->put('/actualizar-precio', \ProductoController::class . ':ActualizarPrecioProducto')
    ->add(new AuditoriaMW())
    ->add(new ValidadorProductosMW(ModoValidacionProductos::ActualizacionPrecio))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// DELETE /////////////////////////////////////////////////

    $group->delete('/borrar', \ProductoController::class . ':BorrarProducto')
    ->add(new AuditoriaMW())
    ->add(new ValidadorProductosMW(ModoValidacionProductos::Borrado))
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));
});

$app->group('/pedidos', function (RouteCollectorProxy $group) 
{
    //////////////////////////////////////////// GET /////////////////////////////////////////////////

    $group->get('[/]', \PedidoController::class . ':ObtenerTodosPedidosConEstados')
    ->add(new AuditoriaMW())
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/tiempo-restante', \PedidoController::class . ':ObtenerTiempoRestante')
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::TiempoRestante));

    $group->get('/obtener-pendientes', \PedidoController::class . ':ObtenerPedidosPendientesSegunSector')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["CERVECERO", "BARTENDER", "COCINERO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/obtener-pedidos-tomados-mozo', \PedidoController::class . ':ObtenerPedidosTomadosMozo')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["MOZO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/obtener-pedidos-tomados-empleado', \PedidoController::class . ':ObtenerPedidosTomadosEmpleado')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["CERVECERO", "BARTENDER", "COCINERO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/obtener-pedidos-listos', \PedidoController::class . ':ObtenerPedidosListosMozo')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["MOZO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/demoras-productos', \PedidoController::class . ':ObtenerDemorasProductos')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/demoras-pedidos', \PedidoController::class . ':ObtenerDemorasPedidos')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/operaciones-por-sector-empleados', \PedidoController::class . ':ObtenerOperacionesPorSectorEmpleados')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/operaciones-por-sector', \PedidoController::class . ':ObtenerOperacionesPorSector')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/operaciones-por-empleados', \PedidoController::class . ':ObtenerOperacionesPorEmpleados')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/productos-mas-vendidos', \PedidoController::class . ':ObtenerProductosOrdenadosPorVentasMayorAMenor')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/productos-menos-vendidos', \PedidoController::class . ':ObtenerProductosOrdenadosPorVentasMenorAMayor')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/cancelados', \PedidoController::class . ':ObtenerPedidosCancelados')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->get('/foto', \PedidoController::class . ':ObtenerFoto')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::ObtenerFoto))
    ->add(new PermisosMW(["ADMIN", "MOZO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    $group->post('/registrar', \PedidoController::class . ':RegistrarPedidoYActualizarMesa')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::Registro))
    ->add(new PermisosMW(["MOZO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->post('/foto', \PedidoController::class . ':VincularFoto')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::Foto))
    ->add(new PermisosMW(["MOZO", "ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

    $group->put('/tomar', \PedidoController::class . ':TomarPedido')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::TomarPedido))
    ->add(new PermisosMW(["CERVECERO", "BARTENDER", "COCINERO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/terminar', \PedidoController::class . ':TerminarPedido')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::TerminarPedido))
    ->add(new PermisosMW(["CERVECERO", "BARTENDER", "COCINERO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/cobrar', \PedidoController::class . ':CobrarMesa')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::Cobrar))
    ->add(new PermisosMW(["MOZO"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/cancelar-todo', \PedidoController::class . ':CancelarTodo')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::CancelarTodo))
    ->add(new PermisosMW(["MOZO", "ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    $group->put('/cancelar-uno', \PedidoController::class . ':CancelarUno')
    ->add(new AuditoriaMW())
    ->add(new ValidadorPedidosMW(ModoValidacionPedidos::CancelarUno))
    ->add(new PermisosMW(["MOZO", "ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));
});

$app->group('/reseñas', function (RouteCollectorProxy $group) 
{
    //////////////////////////////////////////// GET /////////////////////////////////////////////////

    $group->get('/top', \ReseñaController::class . ':ObtenerMejoresComentariosPromedio')
    ->add(new AuditoriaMW())
    ->add(new PermisosMW(["ADMIN"]))
    ->add(new JWTMW(ModoJWT::VerificarToken));

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    $group->post('/registrar', \ReseñaController::class . ':RegistrarReseña')
    ->add(new ValidadorReseñasMW(ModoValidacionReseñas::Registro));
});

$app->get('[/]', function (Request $request, Response $response) 
{    
    $payload = json_encode(array("mensaje" => "Bienvenido a la comanda papaaa"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();