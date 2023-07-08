<?php
    namespace pw2s3\clinicaveterinaria\domain\util;

    use BadFunctionCallException;

    class IllegalOperationException extends BadFunctionCallException {
        public function __construct($msg) {
            parent::__construct($msg);
        }
    }
?>
