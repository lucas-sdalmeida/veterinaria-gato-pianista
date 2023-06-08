<?php
    namespace pw2s3\clinicaveterinaria\model\services\tutor;

    use pw2s3\clinicaveterinaria\model\services\MethodHandler;
    use pw2s3\clinicaveterinaria\model\repository\mysql\MySQLTutorDAO;
    use pw2s3\clinicaveterinaria\model\repository\mysql\MySQLUserAccountDAO;
    use pw2s3\clinicaveterinaria\domain\entities\tutor\Tutor;
    use pw2s3\clinicaveterinaria\model\request\HTTPMethod;
    use pw2s3\clinicaveterinaria\model\request\Response;
    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\HTTPUtils;
    use Exception;

    final class GetTutorMethodHandler implements MethodHandler {
        public static array $VALID_PARAMETERS = [ "tutorId", "accountId" ];

        public function handle(Request $request) : Response {
            if (!$request->hasParameters())
                return $this->handleGetAll();

            if (!static::hasRequiredParameters($request))
                return HTTPUtils::generateErrorReponse(400, "Either no parameters are sent or a " . 
                                "tutorId or accountId must be provided!");

            if ($request->hasParameterByName("tutorId") && $request->hasParameterByName("accountId"))
                return $this->handleGetByIdAndAccount($request->getParameterByName("tutorId"), 
                                    intval($request->getParameterByName("accountId")));
            
            if ($request->hasParameterByName("tutorId"))
                return $this->handleGetById($request->getParameterByName("tutorId"));

            return $this->handleGetByAccount(intval($request->getParameterByName("accountId")));
        }

        private function handleGetAll() : Response {
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

        private function handleGetByIdAndAccount(int $tutorId, int $accountId) : Response {
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

        private function handleGetByAccount(int $accountId) : Response {
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

        private function handleGetById(int $tutorId) : Response {
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

            if ($tutor == null);

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
