<?php
    namespace pw2s3\clinicaveterinaria\model\auth;

    use Exception;

    class InvalidTokenException extends Exception {
        public function __construct(string $message) {
            parent::__construct($message);
        }
    }
?>
