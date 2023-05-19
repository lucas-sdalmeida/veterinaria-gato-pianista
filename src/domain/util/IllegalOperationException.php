<?php
    namespace domain\util;

    use BadFunctionCallException;

    class IllegalOperationException extends BadFunctionCallException {
        public function __construct($msg) {
            parent::__construct($msg);
        }
    }
?>
