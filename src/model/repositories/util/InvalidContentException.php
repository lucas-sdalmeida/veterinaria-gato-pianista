<?php
    namespace pw2s3\clinicaveterinaria\model\repository\util;

    use Exception;

    class InvalidContentException extends Exception {
        public function __construct(string $message) {
            parent::__construct($message);
        }
    }
?>
