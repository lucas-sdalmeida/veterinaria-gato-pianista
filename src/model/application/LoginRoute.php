<?php
    namespace pw2s3\clinicaveterinaria\model\application;

use pw2s3\clinicaveterinaria\model\request\HTTPUtils;
use pw2s3\clinicaveterinaria\model\request\Request;
use pw2s3\clinicaveterinaria\model\request\Response;
use pw2s3\clinicaveterinaria\model\router\Route;
use pw2s3\clinicaveterinaria\model\application\UserSession;

    final class LoginRoute implements Route {
        private const ROUTE_PATH_REGEX = '/veterinaria-gato-pianista\/login\/?$/';

        public function redirectRequest(Request $request): Response {
            if ($request->hasEncodedAuthToken())
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
