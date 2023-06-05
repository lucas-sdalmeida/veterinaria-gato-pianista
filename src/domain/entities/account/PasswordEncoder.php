<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\account;

    final class PasswordEncoder {
        public static function encode(string $password) : string {
            return password_hash($password, "BCrypt", ["cost" => 17]);
        }

        public static function validatePassword(string $password, string $hash) : bool {
            return password_verify($password, $hash);
        }
    }
?>
