<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\services\tutor;

    use lucassdalmeida\gatopianista\veterinaria\model\services\ServicesRequestReader;
    use lucassdalmeida\gatopianista\veterinaria\model\router\WrongRouteException;

    final class TutorRequestReader extends ServicesRequestReader {
        private const TUTOR_PATH_REGEX = '/veterinaria-gato-pianista\/tutor(\/(\d+))?\/?(\?.*)?$/';

        protected function readRestParameters(): array {
            $matches = [];
            preg_match(self::TUTOR_PATH_REGEX, $_SERVER["REQUEST_URI"], $matches);
            $numberOfMatches = count($matches);

            if ($numberOfMatches == 0)
                 throw new WrongRouteException("This is not Tutor service route!");

            $parameters = [];
            if ($numberOfMatches >= 2 && !empty($matches[2]))
                 $parameters["tutorId"] = intval($matches[2]);

            return $parameters;
        }
    }
?>
