<?php
    namespace lucassdalmeida\gatopianista\veterinaria\domain\entities\account\password;

    use InvalidArgumentException;

    final class Password {
        private static array $instances = [];
        private readonly string $password;

        private function __construct(string $password) {
            $this->password = $password;
        }

        public static function valueOf(string $password) {
            if (empty($password))
                throw new InvalidArgumentException("The password cannot be an empty string!");
            if (!array_key_exists($password, static::$instances))
                static::$instances[$password] = new Password($password);
            return static::$instances[$password];
        }
    }
?>
