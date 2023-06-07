<?php
    namespace pw2s3\clinicaveterinaria\model\router;

    use pw2s3\clinicaveterinaria\model\request\Response;

    interface Route {
        function follow() : Response;

        function isRoute(string $path) : bool;
    }
?>
