<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\repository\util;

    interface DatabaseBuilder {
        function build() : void;

        function databaseExists() : bool;
    }
?>
