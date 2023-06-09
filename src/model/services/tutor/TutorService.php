<?php
    namespace pw2s3\clinicaveterinaria\model\services\tutor;

    use pw2s3\clinicaveterinaria\model\auth\Session;
    use pw2s3\clinicaveterinaria\model\request\HTTPUtils;
    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\Response;
    use pw2s3\clinicaveterinaria\model\request\HTTPMethod;
    use pw2s3\clinicaveterinaria\model\router\Route;

    final class TutorService implements Route {
        private const SERVICE_PATH_REGEX = '/veterinaria-gato-pianista\/tutor\/?/';
        private static array $METHOD_HANDLERS = [];

        public function __construct() {
            static::initializeRoutes();
        }

        private static function initializeRoutes() : void {
            if (count(static::$METHOD_HANDLERS) > 0)
                return;
            
            static::$METHOD_HANDLERS[HTTPMethod::GET->value] = new GetTutorMethodHandler();
            static::$METHOD_HANDLERS[HTTPMethod::POST->value] = new PostTutorMethodHandler();
            static::$METHOD_HANDLERS[HTTPMethod::PUT->value] = new PutTutorMethodHandler();
        }

        public function redirectRequest(Request $request, ?Session $session=null): Response {
            if ($session == null)
                return HTTPUtils::generateErrorReponse(401, "You must log-in!");
            if (!array_key_exists($request->getMethod()->value, static::$METHOD_HANDLERS))
                return HTTPUtils::generateErrorReponse(400, "Unknown method <" . $request->getMethod()->value . ">!");

            $requestReader = new TutorRequestReader();
            $request = $requestReader->upgradeRequest($request);

            return static::$METHOD_HANDLERS[$request->getMethod()->value]->handle($request);
        }

        public function isRoute(string $path) : bool {
            return preg_match(self::SERVICE_PATH_REGEX, $path) > 0;
        }
    }
?>
