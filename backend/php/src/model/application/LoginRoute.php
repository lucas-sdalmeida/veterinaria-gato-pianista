<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\application;

    use lucassdalmeida\gatopianista\veterinaria\model\request\HTTPUtils;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Response;
    use lucassdalmeida\gatopianista\veterinaria\model\router\Route;
    use lucassdalmeida\gatopianista\veterinaria\model\application\UserSession;
use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;

    final class LoginRoute implements Route {
        private const ROUTE_PATH_REGEX = '/veterinaria-gato-pianista\/login\/?$/';

        public function redirectRequest(Request $request, ?Session $session=null): Response {
            if ($request->hasEncodedAuthToken() || $session != null)
                return HTTPUtils::generateErrorReponse(400, "There already is a session for this user!");

            $authorizator = new ApplicationAuthorizator();
            $session = $authorizator->createSession($request);

            if (!($session instanceof UserSession))
                return HTTPUtils::generateErrorReponse(500, "Something happened when try to create a session!");

            $tokenEncoder = new ApplicationTokenEncoder();

            $response = new Response(200);
            $response->addContent("token", $tokenEncoder->encode($session->getToken()));
            $response->addContent("accountId", $session->getAccount()->getId());
            $response->addContent("username", $session->getAccount()->getUsername());

            return $response;
        }

        public function isRoute(string $path): bool {
            return preg_match(self::ROUTE_PATH_REGEX, $path);
        }
    }
?>
