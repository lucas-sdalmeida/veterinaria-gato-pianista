<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\auth;

    abstract class Session {
        private readonly Token $token;

        public function __construct(Token $token) {
            $this->token = $token;
        }

        public abstract function hasEnoughtAccessLevel(mixed $requiredAccessLevel) : bool;

        public final function getToken() : Token {
            return $this->token;
        }
    }
?>
