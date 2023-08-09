<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\application;

    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Response;
    use lucassdalmeida\gatopianista\veterinaria\model\router\Route;

    final class ErrorRoute implements Route {
        private const RIGHT_ROUTE_PATH = '/veterinaria-gato-pianista\/[^(tutor|animal|doctor|appointment|login).*]$/';

        public function redirectRequest(Request $request, ?Session $session = null) : Response {
            $response = new Response(404);
            $response->addContent("cause", "There is not such path '" . $request->getURI() . "'!");

            return $response;
        }

        public function isRoute(string $path): bool {
            return preg_match(self::RIGHT_ROUTE_PATH, $path) == 0;
        }
    }
?>
