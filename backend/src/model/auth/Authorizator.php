<?php
    namespace pw2s3\clinicaveterinaria\model\auth;

    use pw2s3\clinicaveterinaria\model\request\Request;

    interface Authorizator {
        function createSession(Request $request) : Session;

        function getSession(Request $request) : Session;
    }
?>
