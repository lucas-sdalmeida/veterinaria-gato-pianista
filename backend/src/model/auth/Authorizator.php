<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\auth;

    use lucassdalmeida\gatopianista\veterinaria\model\request\Request;

    interface Authorizator {
        function createSession(Request $request) : Session;

        function getSession(Request $request) : Session;
    }
?>
