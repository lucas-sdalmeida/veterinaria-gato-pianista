<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\services;

    use lucassdalmeida\gatopianista\veterinaria\model\auth\Session;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;
    use lucassdalmeida\gatopianista\veterinaria\model\request\Response;

    interface MethodHandler {
        function handle(Request $request, Session $session) : Response;
    }
?>
