<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\application;

    use lucassdalmeida\gatopianista\veterinaria\model\auth\Authorizator;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\InvalidTokenException;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\Token;
    use lucassdalmeida\gatopianista\veterinaria\model\repository\mysql\MySQLUserAccountDAO;
    use InvalidArgumentException;
    use DateTimeImmutable;

    final class ApplicationAuthorizator implements Authorizator {
        public function createSession(Request $request) : Session {
            if (!$request->hasParameterByName("username") || !$request->hasParameterByName("password"))
                throw new InvalidArgumentException("Username and password are required parameters!");
            
            $accountDAO = new MySQLUserAccountDAO();
            $account = $accountDAO->findOneByUsername($request->getParameterByName("username"));

            if ($account == null)
                return null;

            $token = new Token(new DateTimeImmutable(), "veterinaria-gato-pianista", $account->getId());
            $session = new UserSession($account, $token);

            return $session;
        }

        public function getSession(Request $request) : Session {
            if (!$request->hasEncodedAuthToken())
                throw new InvalidArgumentException("No token has been sent!");

            $tokenEncoder = new ApplicationTokenEncoder();
            $token = $tokenEncoder->decode($request->getEncodedAuthToken());

            $accountDAO = new MySQLUserAccountDAO();
            $account = $accountDAO->findOneByKey($token->getSubject());

            if ($account == null)
                throw new InvalidTokenException("The token sent has not a valid subject!");
            
            return new UserSession($account, $token);
        }
    }
?>
