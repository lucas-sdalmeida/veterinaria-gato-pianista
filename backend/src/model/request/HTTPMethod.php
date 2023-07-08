<?php
    namespace pw2s3\clinicaveterinaria\model\request;

    enum HTTPMethod : string {
        case GET = "GET";
        case POST = "POST";
        case PUT = "PUT";
        case DELETE = "DELETE";
    }
?>
