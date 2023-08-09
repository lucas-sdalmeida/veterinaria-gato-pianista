<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\router;

    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;

    interface Router {
        function findRouteByRequest(Request $request) : Route;
    }
?>
