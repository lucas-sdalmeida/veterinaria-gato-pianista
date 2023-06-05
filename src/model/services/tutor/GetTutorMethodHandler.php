<?php
    namespace pw2s3\clinicaveterinaria\model\services\tutor;

    use pw2s3\clinicaveterinaria\model\services\MethodHandler;
    use pw2s3\clinicaveterinaria\model\repository\mysql\MySQLTutorDAO;
    use pw2s3\clinicaveterinaria\model\repository\mysql\MySQLUserAccountDAO;
    use pw2s3\clinicaveterinaria\domain\entities\tutor\Tutor;
    use pw2s3\clinicaveterinaria\model\request\HTTPMethod;
    use pw2s3\clinicaveterinaria\model\request\Response;
    use pw2s3\clinicaveterinaria\model\request\Request;
    use Exception;
    use InvalidArgumentException;

    final class GetTutorMethodHandler implements MethodHandler {
        public function handle(Request $request) : Response {
            if ($request->getMethod() != HTTPMethod::GET) {
                $response = new Response(500);
                $response->addContent("cause", "The server got the wrong route! Get route instead of " . 
                    $request->getMethod()->value . " route!"
                );

                return $response;
            }

            if (!$request->hasParameters())
                return $this->getAllTutors();

            if (!$request->hasParameterByName("tutorId") && !$request->hasParameterByName("accountId")) {
                $response = new Response(400);
                $response->addContent("cause", "It is only supported a tutor id param and/or a account id!");

                return $response;
            }
            
            if ($request->hasParameterByName("tutorId") && $request->hasParameterByName("accountId"))
                return $this->getTutorByIdAndUserAccount($request->getParameterByName("tutorId"),
                                                            intval($request->getParameterByName("accountId")));
            if ($request->hasParameterByName("tutorId"))
                return $this->getTutorById($request->getParameterByName("tutorId"));
            
            return $this->getTutorByUserAccount(intval($request->getParameterByName("accountId")));
        }

        public function getTutorById(int $id) : Response {
            $tutorDAO = new MySQLTutorDAO();
            $response = new Response();

            try {
                $tutor = $tutorDAO->findOneByKey($id);

                if ($tutor == null) {
                    $response->setCode(400);
                    $response->addContent("cause", "There is not such tutor!");
                    return $response;
                }

                $response->addContent("data", [ static::tutorToArray($tutor) ]);
            }
            catch (Exception $error) {
                $response->setCode(500);
                $response->addContent("cause", $error->getMessage());
            }
            finally {
                return $response;
            }
        }

        public function getTutorByUserAccount(int $accountId) : Response {
            $accountDAO = new MySQLUserAccountDAO();
            $tutorDAO = new MySQLTutorDAO();
            $response = new Response();

            try {
                $account = $accountDAO->findOneByKey($accountId);

                if ($account == null) {
                    $response->setCode(404);
                    $response->addContent("cause", "There is not such account!");
                    return $response;
                }

                $tutor = $tutorDAO->findOneByUserAccount($account);

                if ($tutor == null) {
                    $response->setCode(400);
                    $response->addContent("cause", "This account does not belongs to any tutor!");
                    return $response;
                }

                $response->addContent("data", [ static::tutorToArray($tutor) ]);
            }
            catch (Exception $error) {
                $response->setCode(500);
                $response->addContent("cause", $error->getMessage());
            }
            finally {
                return $response;
            }
        }

        public function getTutorByIdAndUserAccount(int $id, int $accountId) : Response {
            $tutorDAO = new MySQLTutorDAO();
            $accountDAO = new MySQLUserAccountDAO();
            $response = new Response();

            try {
                $account = $accountDAO->findOneByKey($accountId);

                if ($account == null) {
                    $response->setCode(404);
                    $response->addContent("cause", "There is not such account!");
                    return $response;
                }

                $tutor = $tutorDAO->findOneByKeyAndUserAccount($id, $account);

                if ($tutor == null) {
                    $response->setCode(400);
                    $response->addContent("cause", "This account does not belongs to this tutor!");
                    return $response;
                }

                $response->addContent("data", [ static::tutorToArray($tutor) ]);
            }
            catch (Exception $error) {
                $response->setCode(500);
                $response->addContent("cause", $error->getMessage());
            }
            finally {
                return $response;
            }
        }

        public function getAllTutors() : Response {
            $tutorDAO = new MySQLTutorDAO();
            $response = new Response();

            try {
                $tutors = $tutorDAO->findAll();

                if (count($tutors) == 0) {
                    $response->setCode(404);
                    $response->addContent("cause", "None tutor has been registered yet!");
                    return $response;
                }

                $tutorsAsArray = [];

                foreach($tutors as $tutor)
                    $tutorsAsArray[] = static::tutorToArray($tutor);

                $response->addContent("data", $tutorsAsArray);
            }
            catch (Exception $error) {
                $response->setCode(500);
                $response->addContent("cause", $error->getMessage());
            }
            finally {
                return $response;
            }
        }

        public static function tutorToArray(Tutor $tutor) : array {
            return [
                "id" => $tutor->getId(),
                "name" => $tutor->getName(),
                "cpf" => $tutor->getCPF()->getCPFNumber(),
                "dateOfBirth" => $tutor->getDateOfBirth()->format("Y-m-d"),
                "registrationDate" => $tutor->getRegistrationDate()->format("Y-m-d"),
                "status" => $tutor->getStatus()->value
            ];
        }
    }
?>
