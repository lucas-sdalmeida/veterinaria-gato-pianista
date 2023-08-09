<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\account\password;

    interface PasswordEncoder {
        function encode(string $password) : Password;
    }
?>
