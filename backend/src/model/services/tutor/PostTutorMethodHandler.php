<?php
    namespace pw2s3\clinicaveterinaria\model\services\tutor;

    use pw2s3\clinicaveterinaria\domain\entities\tutor\Tutor;
    use pw2s3\clinicaveterinaria\model\repository\mysql\MySQLTutorDAO;
    use pw2s3\clinicaveterinaria\model\services\MethodHandler;
    use pw2s3\clinicaveterinaria\model\request\HTTPMethod;
    use pw2s3\clinicaveterinaria\model\request\HTTPUtils;
    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\Response;
    use pw2s3\clinicaveterinaria\model\auth\Session;
    use pw2s3\clinicaveterinaria\domain\entities\account\UserRole;
    use DateTimeImmutable;
    use Exception;

    final class PostTutorMethodHandler implements MethodHandler {
        private static array $REQUIRED_PARAMETERS = [ "name", "cpf", "phoneNumber", "dateOfBirth" ];
        private static UserRole $MIN_LEVEL = UserRole::EMPLOYEE;

        public function handle(Request $request, Session $session) : Response {
            $missingParameters = static::validateRequest($request);

            if (!$session->hasEnoughtAccessLevel(static::$MIN_LEVEL))
                return HTTPUtils::generateErrorReponse(403, "You do not have permission to perform this action!");
            if (!empty($missingParameters))
                return HTTPUtils::generateErrorReponse(400, $missingParameters);

            $tutor = new Tutor($request->getParameterByName("name"), $request->getParameterByName("cpf"),
                               $request->getParameterByName("phoneNumber"), 
                               DateTimeImmutable::createFromFormat("Y-m-d", $request->getParameterByName("dateOfBirth"))
                            );
            $tutorDAO = new MySQLTutorDAO();
            $tutorId = 0;

            try {
                if ($tutorDAO->findOneByCPF($tutor->getCPF()) != null)
                    return HTTPUtils::generateErrorReponse(400, "Such tutor already exists!");

                $tutorDAO->insert($tutor);
                $tutor = $tutorDAO->findOneByCPF($tutor->getCPF());

                if ($tutor == null)
                    return HTTPUtils::generateErrorReponse(500, "Could not registry this Tutor!");
                
                $tutorId = $tutor->getId();
            }
            catch(Exception $error) {
                return HTTPUtils::generateErrorReponse(500, $error->getMessage());
            }

            $response = new Response(201);
            $response->addContent("url", "veterinaria-gato-pianista/tutor/$tutorId");

            return $response;
        }

        public static function validateRequest(Request $request) : string {
            $missingParameters = array_diff(static::$REQUIRED_PARAMETERS, array_keys($request->getAllParameters()));

            if (count($missingParameters) == 0)
                return "";

            return "The following parameters are required and are missing: " . implode(", ", $missingParameters) . ".";
        }
    }
?>
