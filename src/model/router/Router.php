<?php
    namespace pw2s3\clinicaveterinaria\model\router;

    use pw2s3\clinicaveterinaria\model\request\Request;

    interface Router {
        function findRouteByRequest(Request $request) : Route;
    }
?>
