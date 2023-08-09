<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\application;

    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\router\Route;
    use lucassdalmeida\gatopianista\veterinaria\model\router\Router;
    use lucassdalmeida\gatopianista\veterinaria\model\services\ServicesRouter;

    final class MainRouter implements Router {
        public function findRouteByRequest(Request $request) : Route {
            $servicesRouter = new ServicesRouter();
            $loginRoute = new LoginRoute();

            if ($loginRoute->isRoute($request->getURI()))
                return $loginRoute;

            if ($servicesRouter->isRoute($request->getURI()))
                return $servicesRouter;

            return new ErrorRoute();
        }
    }
?>
