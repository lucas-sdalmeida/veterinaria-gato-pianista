<?php
    namespace pw2s3\clinicaveterinaria\model\repository\util;

    interface DatabaseBuilder {
        function build() : void;

        function databaseExists() : bool;
    }
?>
