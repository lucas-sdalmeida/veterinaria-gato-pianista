<?php
    namespace pw2s3\clinicaveterinaria\domain\entities\account;

use InvalidArgumentException;

    enum UserRole : string {
        case TUTOR = "Tutor";
        case DOCTOR = "Doctor";
        case EMPLOYEE = "Employee";
        case ADMIN = "Admin";

        public static function fromString(string $str) {
            foreach(static::cases() as $role)
                if (strtolower($role->value) === strtolower($str)) return $role;
            throw new InvalidArgumentException(
                "The specified string does not match with any case!"
            );
        }

        public final function compareAccessLevel(UserRole $other) : int {
            return array_search($this, static::cases()) <=> array_search($other, static::cases());
        }
    }
?>
