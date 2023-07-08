<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\model\repository\util\DatabaseBuilder;
    use pw2s3\clinicaveterinaria\model\repository\util\NonDatabaseConnectionFactory;
    use pw2s3\clinicaveterinaria\model\repository\util\FileNotFoundException;
    use PDOException;
    use Exception;

    final class VeterinariaMySQLDatabaseBuilder implements DatabaseBuilder {
        private const PATH_FOR_ACCESS_DATA = __DIR__ . '\..\..\..\resources\xampp-mysql-db-access-data.json';
        private const PATH_FOR_BUILD_SQL_FILE = __DIR__ . '\..\..\..\resources\database.sql';

        public function build() : void {
            if ($this->databaseExists())
                return;
            
            $databaseBuildScript = file_get_contents(self::PATH_FOR_BUILD_SQL_FILE);

            if (!$databaseBuildScript)
                throw new FileNotFoundException(
                    "Could not find the file containing the build sql script! Look for database.sql at src/resources!"
                );

            $connectionFactory = new NonDatabaseConnectionFactory(self::PATH_FOR_ACCESS_DATA);
            
            try {
                $statement = $connectionFactory->prepareStatement($databaseBuildScript);
                $statement->execute();
            }
            catch (PDOException $error)  {
                throw new Exception($error->getMessage());
            }
        }

        public function databaseExists() : bool {
            $connectionFactory = new NonDatabaseConnectionFactory(self::PATH_FOR_ACCESS_DATA);
            $sql = "SELECT schema_name FROM information_schema.schemata WHERE schema_name = 'veterinaria_gato_pianista'";

            try {
                $statement = $connectionFactory->prepareStatement($sql);
                $statement->execute();

                return $statement->rowCount() > 0;
            }
            catch (PDOException $error) {
                throw new Exception($error->getMessage());
            }
        }
    }
?>
