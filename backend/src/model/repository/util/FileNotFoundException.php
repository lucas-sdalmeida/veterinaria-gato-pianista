<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\repository\util;

    use Exception;

    class FileNotFoundException extends Exception {
        public function __construct(string $message) {
            parent::__construct($message);
        }
    }
?>
