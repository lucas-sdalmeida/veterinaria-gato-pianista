<?php
    require_once(__DIR__ . "/../vendor/autoload.php");

    use pw2s3\clinicaveterinaria\model\application\MainRouter;
    use pw2s3\clinicaveterinaria\model\request\JSONRequestReader;
    use pw2s3\clinicaveterinaria\model\request\JSONResponseSender;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");

    $requestReader = new JSONRequestReader();
    $request = $requestReader->readRequest();

    $router = new MainRouter();
    $route = $router->findRouteByRequest($request);
    
    $response = $route->redirectRequest($request);
    $responseSender = new JSONResponseSender();

    $responseSender->sendResponse($response);
?>
