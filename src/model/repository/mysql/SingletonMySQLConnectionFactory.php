<?php
    namespace pw2s3\clinicaveterinaria\model\repository\mysql;

    use pw2s3\clinicaveterinaria\persistence\util\ConnectionFactory;
    use pw2s3\clinicaveterinaria\model\repository\util\DBAccessDataProvider;
    use PDO;

    class SingletonMySQLConnectionFactory extends ConnectionFactory {
        private const PATH = __DIR__ . "/../../../resources/mysql-db-access-data.json";
        private const PDO_OPTIONS = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                      PDO::ATTR_EMULATE_PREPARES => false
                                    ];

        private static ?PDO $connection = null;

        public function getConnection() : PDO {
            if (static::$connection == null){
                $access = DBAccessDataProvider::initialize(self::PATH);
                static::$connection = new PDO($access->getURL(), $access->getUsername(), 
                                            $access->getPassword(), self::PDO_OPTIONS);
            }

            return static::$connection;
        }
    }
?>
