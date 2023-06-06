<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\model\repository\util\DBAccessDataProvider;
    use Exception;
    use PDO;

    final class DatabaseBuilder {
        public static function build() : void {
            $accessDataProvider = DBAccessDataProvider::initialize(__DIR__ . 
                    "/../../../resources/mysql-db-access-data.json");
            $connection = new PDO(
                str_replace(";dbname=veterinaria_gato_pianista", "", $accessDataProvider->getURL()),
                $accessDataProvider->getUsername(), $accessDataProvider->getPassword()
            );
            $sql = file_get_contents(__DIR__ . "/../../../resources/database.sql");

            try {
                $statement = $connection->prepare($sql);
                $statement->execute([]);
            }
            catch (Exception $error) {
                return;
            }
        }
    }
?>
