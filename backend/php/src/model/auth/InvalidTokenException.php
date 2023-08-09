<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\auth;

    use Exception;

    class InvalidTokenException extends Exception {
        public function __construct(string $message) {
            parent::__construct($message);
        }
    }
?>
