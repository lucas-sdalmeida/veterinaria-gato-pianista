<?php
    require_once(__DIR__ . "/../vendor/autoload.php");

    use pw2s3\clinicaveterinaria\model\repository\mysql\SingletonMySQLConnectionFactory;

    $connection = (new SingletonMySQLConnectionFactory())->getConnection();
?>
