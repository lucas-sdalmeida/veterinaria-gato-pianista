<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\request;

    enum HTTPMethod : string {
        case GET = "GET";
        case POST = "POST";
        case PUT = "PUT";
        case DELETE = "DELETE";
    }
?>
