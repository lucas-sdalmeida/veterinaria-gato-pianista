<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\services\tutor;

    use lucassdalmeida\gatopianista\veterinaria\model\services\MethodHandler;
    use lucassdalmeida\gatopianista\veterinaria\model\repository\mysql\MySQLTutorDAO;
    use lucassdalmeida\gatopianista\veterinaria\model\repository\mysql\MySQLUserAccountDAO;
    use lucassdalmeida\gatopianista\veterinaria\domain\entities\tutor\Tutor;
    use lucassdalmeida\gatopianista\veterinaria\model\request\HTTPMethod;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Response;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\request\HTTPUtils;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\application\UserSession;
    use lucassdalmeida\gatopianista\veterinaria\domain\entities\account\UserRole;
    use Exception;

    final class GetTutorMethodHandler implements MethodHandler {
        public static array $VALID_PARAMETERS = [ "tutorId", "accountId" ];
        public static UserRole $LEVEL_FOR_GET_ALL = UserRole::DOCTOR;
        public static UserRole $LEVEL_FOR_GET_ONE = UserRole::TUTOR;

        public function handle(Request $request, Session $session) : Response {
            if (!$request->hasParameters())
                return $this->handleGetAll($session);

            if (!static::hasRequiredParameters($request, $session))
                return HTTPUtils::generateErrorReponse(400, "Either no parameters are sent or a " . 
                                "tutorId or accountId must be provided!");

            if ($request->hasParameterByName("tutorId") && $request->hasParameterByName("accountId"))
                return $this->handleGetByIdAndAccount($request->getParameterByName("tutorId"), 
                                    intval($request->getParameterByName("accountId")), $session);
            
            if ($request->hasParameterByName("tutorId"))
                return $this->handleGetById($request->getParameterByName("tutorId"), $session);

            return $this->handleGetByAccount(intval($request->getParameterByName("accountId")), $session);
        }

        private function handleGetAll(Session $session) : Response {
            if (!$session->hasEnoughtAccessLevel(static::$LEVEL_FOR_GET_ALL))
                return HTTPUtils::generateErrorReponse(403, "You do not have permission to perform this action!");
            try {
                $tutors = $this->getAllTutors();

                if (count($tutors) == 0)
                    return HTTPUtils::generateErrorReponse(404, "No tutor has been registered!");

                $response = new Response();
                $response->addContent("data", $tutors);
                return $response;
            }
            catch(Exception $error) {
                return HTTPUtils::generateErrorReponse(500, $error->getMessage());
            }
        }

        private function handleGetByIdAndAccount(int $tutorId, int $accountId, Session $session) : Response {
            if (!$session->hasEnoughtAccessLevel(static::$LEVEL_FOR_GET_ONE) || 
                    $session->getToken()->getSubject() != $accountId)
                return HTTPUtils::generateErrorReponse(403, "You do not have permission to perform this action!");

            try {
                $tutor = $this->getTutorByIdAndAccount($tutorId, $accountId);

                if (count($tutor) == 0)
                    return HTTPUtils::generateErrorReponse(404, "This account does not belongs to such tutor!");

                $response = new Response();
                $response->addContent("data", [ $tutor ]);
                
                return $response;
            }
            catch (Exception $error) {
                return HTTPUtils::generateErrorReponse(500, $error->getMessage());
            }
        }

        private function handleGetByAccount(int $accountId, Session $session) : Response {
            if ($session->hasEnoughtAccessLevel(static::$LEVEL_FOR_GET_ONE) || 
                    $session->getToken()->getSubject() != $accountId)
                return HTTPUtils::generateErrorReponse(403, "You do not have permission to perform this action!");
            try {
                $tutor = $this->getTutorByAccount($accountId);

                if (count($tutor) == 0)
                    return HTTPUtils::generateErrorReponse(404, "There is not a tutor with such account!");

                $response = new Response();
                $response->addContent("data", [ $tutor ]);

                return $response;
            }
            catch (Exception $error) {
                return HTTPUtils::generateErrorReponse(500, $error->getMessage());
            }
        }

        private function handleGetById(int $tutorId, Session $session) : Response {
            if (!$session->hasEnoughtAccessLevel(UserRole::DOCTOR))
                    return $this->handleGetByIdAndAccount($tutorId, $session->getToken()->getSubject(), $session);
            try {

                $tutor = $this->getTutorById($tutorId);

                if (count($tutor) == 0)
                    return HTTPUtils::generateErrorReponse(404, "There is not such tutor!");

                $response = new Response();
                $response->addContent("data", [ $tutor ]);

                return $response;
            }
            catch (Exception $error) {
                return HTTPUtils::generateErrorReponse(500, $error->getMessage());
            }
        }

        public function getAllTutors() : array {
            $tutorDAO = new MySQLTutorDAO();
            $tutors = $tutorDAO->findAll();
            $tutorsAsArray = [];

            foreach($tutors as $tutor)
                $tutorsAsArray[] = static::tutorToArray($tutor);

            return $tutorsAsArray;
        }

        public function getTutorByIdAndAccount(int $tutorId, int $accountId) : array {
            $tutorDAO = new MySQLTutorDAO();
            $accountDAO = new MySQLUserAccountDAO();

            $account = $accountDAO->findOneByKey($accountId);

            if ($account == null)
                return [];

            $tutor = $tutorDAO->findOneByKeyAndUserAccount($tutorId, $account);

            if ($tutor == null)
                return [];

            return static::tutorToArray($tutor);
        }

        public function getTutorByAccount(int $accountId) : array {
            $tutorDAO = new MySQLTutorDAO();
            $accountDAO = new MySQLUserAccountDAO();

            $account = $accountDAO->findOneByKey($accountId);

            if ($account == null)
                return [];

            $tutor = $tutorDAO->findOneByUserAccount($account);

            if ($tutor == null)
                return [];

            return static::tutorToArray($tutor);
        }

        public function getTutorById(int $tutorId) : array {
            $tutorDAO = new MySQLTutorDAO();
            
            $tutor = $tutorDAO->findOneByKey($tutorId);

            if ($tutor == null)
                return [];
            
            return static::tutorToArray($tutor);
        }

        public static function tutorToArray(Tutor $tutor) : array {
            return [
                "id" => $tutor->getId(),
                "name" => $tutor->getName(),
                "cpf" => $tutor->getCPF()->getCPFNumber(),
                "phoneNumber" => $tutor->getPhoneNumber(),
                "dateOfBirth" => $tutor->getDateOfBirth()->format("Y-m-d"),
                "registrationDate" => $tutor->getRegistrationDate()->format('Y-m-d'),
                "status" => $tutor->getStatus()
            ];
        }

        public static function hasRequiredParameters(Request $request) : bool {
            $missingParameters = array_diff(static::$VALID_PARAMETERS, array_keys($request->getAllParameters()));

            return count($missingParameters) < count(static::$VALID_PARAMETERS);
        }
    }
?>
