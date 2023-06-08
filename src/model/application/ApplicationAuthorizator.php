<?php
    namespace pw2s3\clinicaveterinaria\model\application;

    use pw2s3\clinicaveterinaria\model\auth\Authorizator;
    use pw2s3\clinicaveterinaria\model\auth\InvalidTokenException;
    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\auth\Session;
    use pw2s3\clinicaveterinaria\model\auth\Token;
    use pw2s3\clinicaveterinaria\model\repository\mysql\MySQLUserAccountDAO;
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
