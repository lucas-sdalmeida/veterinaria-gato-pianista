<?php
    require_once(__DIR__ . "/../vendor/autoload.php");

    use pw2s3\clinicaveterinaria\model\request\JSONRequestReader;
    
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");

    $reader = new JSONRequestReader();

    $request = $reader->readRequest();

    echo json_encode($request->getAllParameters());
?>
