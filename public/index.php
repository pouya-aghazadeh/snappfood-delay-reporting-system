<?php

use Medoo\Medoo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

// DI Container for dependency injection
$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();


// Custom Error Handler
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(function (Request $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails, ?LoggerInterface $logger = null) use ($app) {
    if ($logger)
        $logger->error($exception->getMessage());
    $code = 500;
    if (!empty($exception->getCode()) && is_int($exception->getCode()))
        $code = $exception->getCode();
    $payload = [
        'success' => false,
        'code' => $code,
        'error' => $exception->getMessage()
    ];
    $response = $app->getResponseFactory()->createResponse()->withStatus($code)->withHeader('Content-Type', 'application/json');
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    );
    return $response;
});



// DB Config
$container->set('database', function () {
    return new Medoo([
        'type' => 'mysql',
        'host' => 'snappfood-db',
        'port' => '3306',
        'database' => 'snappfood',
        'username' => 'dba',
        'password' => '1234'
    ]);
});

// Connection Info Route
$app->get('/', function (Request $request, Response $response) {
    $data = $this->get('database')->info();
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});


// DB Actions Routes
$app->post('/migrate/{type}', function (Request $request, Response $response, $arg) {
    foreach (glob(__DIR__ . "/../migrations/" . $arg['type'] . "/*.sql") as $sql_file) {
        $query = file_get_contents($sql_file);
        $this->get('database')->query($query);
    }
    $response->getBody()->write(json_encode([
        'success' => true,
        'message' => 'executed successfully'
    ], JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});


// Business Layer Services Routes
$app->group('/delay-reports', function (RouteCollectorProxy $group) {
    $group->post('/register/{order-id}', \App\ServiceLayer\Controllers\DelayReport::class . ':Register');
    $group->patch('/track/{tracker-id}', \App\ServiceLayer\Controllers\DelayReport::class . ':Track');
    $group->get('/list/{vendor-id}', \App\ServiceLayer\Controllers\DelayReport::class . ':ListByVendorId');
});

// Execute
$app->run();