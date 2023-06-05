<?php
    namespace pw2s3\clinicaveterinaria\model\services\tutor;

    use pw2s3\clinicaveterinaria\model\services\ServicesRequestReader;
    use pw2s3\clinicaveterinaria\model\router\WrongRouteException;

    final class TutorRequestReader extends ServicesRequestReader {
        private const TUTOR_PATH_REGEX = '/veterinaria-gato-pianista\/tutor(\/(\d+)(\/animal(\/(\d+))?)?)?\/?/';

        protected function readRestParameters(): array {
            $matches = [];
            preg_match(self::TUTOR_PATH_REGEX, $_SERVER["REQUEST_URI"], $matches);
            $numberOfMatches = count($matches);

            if ($numberOfMatches == 0)
                 throw new WrongRouteException("This is not Tutor service route!");

            $parameters = [];

            if ($numberOfMatches >= 2)
                 $parameters["tutorId"] = intval($matches[2]);
            if ($numberOfMatches > 5)
                 $parameters["animalId"] = intval($matches[5]);

            return $parameters;
        }
    }
?>
