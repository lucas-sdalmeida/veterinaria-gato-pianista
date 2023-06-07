<?php
    require_once(__DIR__ . "/../vendor/autoload.php");

    use pw2s3\clinicaveterinaria\model\request\JSONRequestReader;
    use pw2s3\clinicaveterinaria\model\request\JSONResponseSender;
    use pw2s3\clinicaveterinaria\model\services\ServicesRouter;

    $requestReader = new JSONRequestReader();
    $request = $requestReader->readRequest();

    $router = new ServicesRouter();
    $route = $router->findRouteByRequest($request);
    
    $response = $route->redirectRequest($request);
    $responseSender = new JSONResponseSender();

    $responseSender->sendResponse($response);
?>
