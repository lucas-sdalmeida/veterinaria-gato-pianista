<?php
    namespace pw2s3\clinicaveterinaria\model\application;

use DateTimeImmutable;
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
                "iat" => $token->getIssueDateTime()->getTimestamp(),
                "iss" => $token->getIssuer(),
                "nbf" => $token->getNotBeforeDateTime()->getTimestamp(),
                "exp" => $token->getExpiringDateTime()->getTimestamp(),
                "sub" => $token->getSubject(),
                "data" => $token->getData()
            ];
        }

        public static function payloadToToken(stdClass $payload) : Token {
            $token = new Token(DateTimeImmutable::createFromFormat("U", $payload->iat), $payload->iss, $payload->sub);
            
            foreach ($payload->data as $fieldName => $data)
                $token->addData($fieldName, strval($data));

            return $token;
        }
    }
?>
