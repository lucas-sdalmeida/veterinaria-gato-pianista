<?php
    namespace pw2s3\clinicaveterinaria\model\router;

    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\Response;

    interface Route {
        function redirectRequest(Request $request) : Response;

        function isRoute(string $path) : bool;
    }
?>
