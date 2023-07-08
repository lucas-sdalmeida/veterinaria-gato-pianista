<?php
    namespace pw2s3\clinicaveterinaria\model\auth;

    interface TokenEncoder {
        function encode(Token $token) : string;

        function decode(string $encodedToken) : Token;
    }
?>
