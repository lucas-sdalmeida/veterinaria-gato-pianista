<?php
    namespace lucassdalmeida\gatopianista\veterinaria\model\repository\util;

    use lucassdalmeida\gatopianista\veterinaria\persistence\util\ConnectionFactory;
    use PDO;

    final class NonDatabaseConnectionFactory extends ConnectionFactory {
        private readonly string $accessDataPath;
        private static ?PDO $connection = null;

        public function __construct(string $accessDataPath) {
            $this->accessDataPath = $accessDataPath;
        }

        public final function getConnection(): PDO {
            if (static::$connection == null) {
                $provider = DBAccessDataProvider::initialize($this->accessDataPath);
                static::$connection = new PDO($provider->getPath(), $provider->getUsername(), $provider->getPassword(),
                                            [ PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ]);
            }
            return static::$connection;
        }
    }
?>
