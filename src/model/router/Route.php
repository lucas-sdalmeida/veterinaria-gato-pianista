<?php
    namespace pw2s3\clinicaveterinaria\model\router;

    interface Route {
        function follow();

        function isRoute(string $path) : bool;
    }
?>
