<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\router;

    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Response;

    interface Route {
        function redirectRequest(Request $request, ?Session $session=null) : Response;

        function isRoute(string $path) : bool;
    }
?>
