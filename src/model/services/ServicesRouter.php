<?php
    namespace pw2s3\clinicaveterinaria\model\services;

    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\Response;
    use pw2s3\clinicaveterinaria\model\router\Route;
    use pw2s3\clinicaveterinaria\model\router\Router;
    use pw2s3\clinicaveterinaria\model\services\tutor\TutorService;
    use pw2s3\clinicaveterinaria\model\application\ErrorRoute;

    final class ServicesRouter implements Router, Route {
        private const ROUTE_PATH_REGEX = '/veterinaria-gato-pianista\/(account|tutor|animal|doctor|appointment)\/?/';
        private static array $SERVICES_ROUTES = [];

        public function __construct() {
            static::initializeRoutes();
        }

        private static function initializeRoutes() : void {
            if (count(static::$SERVICES_ROUTES) > 0)
                return;
                
            static::$SERVICES_ROUTES[] = new TutorService();
        }

        public function findRouteByRequest(Request $request): Route {
            foreach(static::$SERVICES_ROUTES as $service)
                if ($service->isRoute($request->getURI())) return $service;
            return new ErrorRoute();
        }

        public function redirectRequest(Request $request): Response {
            $serviceRoute = $this->findRouteByRequest($request);

            return $serviceRoute->redirectRequest($request);
        }

        public function isRoute(string $path): bool {
            return preg_match(self::ROUTE_PATH_REGEX, $path) > 0;
        }
    }
?>
