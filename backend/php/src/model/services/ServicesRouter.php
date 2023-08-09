<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\services;

    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Response;
    use lucassdalmeida\gatopianista\veterinaria\model\router\Route;
    use lucassdalmeida\gatopianista\veterinaria\model\router\Router;
    use lucassdalmeida\gatopianista\veterinaria\model\services\tutor\TutorService;
    use lucassdalmeida\gatopianista\veterinaria\model\application\ErrorRoute;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\request\HTTPUtils;

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

        public function redirectRequest(Request $request, ?Session $session=null): Response {
            if ($session == null)
                return HTTPUtils::generateErrorReponse(401, "You must log-in!");

            $serviceRoute = $this->findRouteByRequest($request);

            return $serviceRoute->redirectRequest($request, $session);
        }

        public function isRoute(string $path): bool {
            return preg_match(self::ROUTE_PATH_REGEX, $path) > 0;
        }
    }
?>
