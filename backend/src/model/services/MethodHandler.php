<?php
    namespace pw2s3\clinicaveterinaria\model\services;

    use pw2s3\clinicaveterinaria\model\auth\Session;
    use pw2s3\clinicaveterinaria\model\request\Request;
    use pw2s3\clinicaveterinaria\model\request\Response;

    interface MethodHandler {
        function handle(Request $request, Session $session) : Response;
    }
?>
