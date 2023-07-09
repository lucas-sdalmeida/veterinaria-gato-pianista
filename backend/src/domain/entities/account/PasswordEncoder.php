<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\account;

    interface PasswordEncoder {
        function encode(string $password) : string;

        function validatePassword(string $password, string $hash) : bool;
    }
?>
