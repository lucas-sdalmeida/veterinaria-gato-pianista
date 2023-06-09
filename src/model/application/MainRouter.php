<?php
    namespace pw2s3\clinicaveterinaria\model\application;

    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\router\Route;
    use pw2s3\clinicaveterinaria\model\router\Router;
    use pw2s3\clinicaveterinaria\model\services\ServicesRouter;

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
