<?php
    namespace pw2s3\clinicaveterinaria\model\services;

    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\router\Route;
    use pw2s3\clinicaveterinaria\model\router\Router;

    final class ServicesRouter implements Router {
        private const SERVICES_ROUTES = [
            
        ];

        public function findRouteByRequest(Request $request): Route {
            foreach(self::SERVICES_ROUTES as $service)
                if ($service->isRoute($request->getURI())) return $service;
            return new ErrorRoute();
        }
    }
?>
