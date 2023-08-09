<?php
    /*
        Lucas Silva de Almeida.
    */

    require_once(__DIR__ . "/../vendor/autoload.php");

    use lucassdalmeida\gatopianista\veterinaria\model\application\ApplicationAuthorizator;
    use lucassdalmeida\gatopianista\veterinaria\model\application\MainRouter;
    use lucassdalmeida\gatopianista\veterinaria\model\request\JSONRequestReader;
    use lucassdalmeida\gatopianista\veterinaria\model\request\JSONResponseSender;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");

    $requestReader = new JSONRequestReader();
    $request = $requestReader->readRequest();

    $router = new MainRouter();
    $route = $router->findRouteByRequest($request);

    $authorizator = new ApplicationAuthorizator();
    
    try {
        $session = $authorizator->getSession($request);
    }
    catch (Exception $error) {
        $session = null;
    }
    
    $response = $route->redirectRequest($request, $session);
    $responseSender = new JSONResponseSender();

    $responseSender->sendResponse($response);
?>
