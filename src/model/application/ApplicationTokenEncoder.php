<?php
    namespace pw2s3\clinicaveterinaria\model\application;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use pw2s3\clinicaveterinaria\model\auth\Token;
use pw2s3\clinicaveterinaria\model\auth\TokenEncoder;
use stdClass;

    final class ApplicationTokenEncoder implements TokenEncoder {
        private const SECRET_KEY = "jdshfskjdçafgdfdfgad";

        public function encode(Token $token) : string {
            return JWT::encode(static::tokenToArray($token), self::SECRET_KEY, "HS512");
        }

        public function decode(string $encodedToken) : Token {
            $payload = JWT::decode($encodedToken, new Key(self::SECRET_KEY, "HS512"));
            
            return static::payloadToToken($payload);
        }

        public static function tokenToArray(Token $token) : array {
            return [
                "iat" => $token->getIssueDateTime()->format("Y-m-d H:i:s"),
                "iss" => $token->getIssuer(),
                "nbf" => $token->getNotBeforeDateTime()->format("Y-m-d H:i:s"),
                "exp" => $token->getExpiringDateTime()->format("Y-m-d H:i:s"),
                "sub" => $token->getSubject(),
                "data" => $token->getData()
            ];
        }

        public static function payloadToToken(stdClass $payload) : Token {
            return new Token($payload->iat, $payload->iss, $payload->sub, $payload->nbf);
        }
    }
?>
