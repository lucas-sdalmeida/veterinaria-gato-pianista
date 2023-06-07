<?php
    namespace pw2s3\clinicaveterinaria\model\services\util;

    abstract class Validator {
        public abstract function validate(mixed $target) : Notification;
    }
?>
