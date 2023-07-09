<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\auth;

    interface TokenEncoder {
        function encode(Token $token) : string;

        function decode(string $encodedToken) : Token;
    }
?>
