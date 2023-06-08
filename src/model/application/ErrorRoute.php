<?php
    namespace pw2s3\clinicaveterinaria\model\application;

    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\Response;
    use pw2s3\clinicaveterinaria\model\router\Route;

    final class ErrorRoute implements Route {
        private const RIGHT_ROUTE_PATH = '/veterinaria-gato-pianista\/[^(tutor|animal|doctor|appointment|login).*]$/';

        public function redirectRequest(Request $request) : Response {
            $response = new Response(404);
            $response->addContent("cause", "There is not such path '" . $request->getURI() . "'!");

            return $response;
        }

        public function isRoute(string $path): bool {
            return preg_match(self::RIGHT_ROUTE_PATH, $path) == 0;
        }
    }
?>
