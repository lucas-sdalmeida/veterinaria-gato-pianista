<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\request;

    use InvalidArgumentException;

    final class HTTPUtils {
        public const HTTP_CLIENT_ERROR_CODES_FAMILY_RANGE = [400, 499];
        public const HTTP_SERVER_ERROR_CODE_FAMILY_RANGE = [500, 599];

        public static function generateErrorReponse(int $code, string $cause) : Response {
            if (!static::isValidErrorCode($code))
                throw new InvalidArgumentException(
                    "The error code must belong to either the client or the server error code family"
                );

            $response = new Response($code);
            $response->addContent("cause", $cause);

            return $response;
        }

        public static function isValidErrorCode(int $code) : bool {
            if (self::HTTP_CLIENT_ERROR_CODES_FAMILY_RANGE[0] <= $code && 
                    $code <= self::HTTP_CLIENT_ERROR_CODES_FAMILY_RANGE[1])
                return true;
            return self::HTTP_SERVER_ERROR_CODE_FAMILY_RANGE[0] <= $code && 
                        $code <= self::HTTP_SERVER_ERROR_CODE_FAMILY_RANGE[1];
        }
    }
?>
