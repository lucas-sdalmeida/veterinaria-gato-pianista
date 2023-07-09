<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\repository\mysql;

    use lucassdalmeida\gatopianista\veterinaria\persistence\util\ConnectionFactory;
    use lucassdalmeida\gatopianista\veterinaria\model\repository\util\DBAccessDataProvider;
    use PDO;

    class SingletonMySQLConnectionFactory extends ConnectionFactory {
        private const PATH_FOR_ACCESS_DATA = __DIR__ . "/../../../resources/xampp-mysql-db-access-data.json";
        private const PDO_OPTIONS = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                      PDO::ATTR_EMULATE_PREPARES => false
                                    ];

        private static ?PDO $connection = null;

        public function getConnection() : PDO {
            if (static::$connection == null){
                $access = DBAccessDataProvider::initialize(self::PATH_FOR_ACCESS_DATA);

                $builder = new VeterinariaMySQLDatabaseBuilder();
                $builder->build();

                static::$connection = new PDO($access->getDNS(), $access->getUsername(), 
                                            $access->getPassword(), self::PDO_OPTIONS);
            }

            return static::$connection;
        }
    }
?>
