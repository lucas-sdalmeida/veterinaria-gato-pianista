<?php
    namespace pw2s3\clinicaveterinaria\model\router;

    use pw2s3\clinicaveterinaria\model\auth\Session;
    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\Response;

    interface Route {
        function redirectRequest(Request $request, ?Session $session=null) : Response;

        function isRoute(string $path) : bool;
    }
?>
