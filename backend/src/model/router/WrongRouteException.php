<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\router;

    use Exception;

    class WrongRouteException extends Exception {
        public function __construct(string $message) {
            parent::__construct($message);
        }
    }
?>
