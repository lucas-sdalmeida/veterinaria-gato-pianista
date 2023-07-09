<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\services\tutor;

    use lucassdalmeida\gatopianista\veterinaria\domain\entities\tutor\Tutor;
    use lucassdalmeida\gatopianista\veterinaria\model\repository\mysql\MySQLTutorDAO;
    use lucassdalmeida\gatopianista\veterinaria\model\request\HTTPUtils;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Response;
    use lucassdalmeida\gatopianista\veterinaria\model\services\MethodHandler;
    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\application\UserSession;
    use DateTimeImmutable;
    use Exception;

    final class PutTutorMethodHandler implements MethodHandler {
        private static array $REQUIRED_PARAMETERS = [ "name", "phoneNumber", "dateOfBirth" ];

        public function handle(Request $request, Session $session) : Response {
            $getTutorMethodHandler = new GetTutorMethodHandler();
            
            if (!$request->hasParameters())
                return HTTPUtils::generateErrorReponse(400, "Is is only possible to modify data of a" . 
                            " single tutor at time!");

            $missingParameters = static::validateParameters($request);

            if (!empty($missingParameters))
                return HTTPUtils::generateErrorReponse(400, $missingParameters);

            $getTutorResponse = $getTutorMethodHandler->handle($request, $session);

            if (HTTPUtils::isValidErrorCode($getTutorResponse->getCode()))
                return $getTutorResponse;
                
            $responseTutor = $getTutorResponse->getBody()["data"][0];

            $tutor = new Tutor($request->getParameterByName("name"), 
                               $responseTutor["cpf"], 
                               $request->getParameterByName("phoneNumber"), 
                               DateTimeImmutable::createFromFormat("Y-m-d", $request->getParameterByName("dateOfBirth")),
                               DateTimeImmutable::createFromFormat("Y-m-d", $responseTutor["registrationDate"]),
                               $responseTutor["status"],
                               $responseTutor["id"]
                            );

            try {
                $tutorDAO = new MySQLTutorDAO();
                $tutorDAO->update($tutor);
            }
            catch (Exception $error) {
                return HTTPUtils::generateErrorReponse(500, $error->getMessage());
            }

            $response = new Response();
            $response->addContent("url", "See: veterinaria-gato-pianista/tutor/" . $responseTutor["id"]);

            return $response;
        }

        public static function validateParameters(Request $request) : string {
            $missingParameters = array_diff(static::$REQUIRED_PARAMETERS, array_keys($request->getAllParameters()));

            if (count($missingParameters) == 0)
                return "";

            return "The following parameters are mandatory and they are missing: " . 
                        implode(", ", $missingParameters) . ".";
        }
    }
?>
