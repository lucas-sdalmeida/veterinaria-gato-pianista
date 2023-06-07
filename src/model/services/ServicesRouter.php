<?php
    namespace pw2s3\clinicaveterinaria\model\services;

    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\router\Route;
    use pw2s3\clinicaveterinaria\model\router\Router;
    use pw2s3\clinicaveterinaria\model\services\tutor\TutorService;

    final class ServicesRouter implements Router {
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
    }
?>
