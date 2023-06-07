<?php
    namespace pw2s3\clinicaveterinaria\model\repository\util;

    final class DBAccessDataProvider {
        private readonly string $path;
        private readonly string $database;
        private readonly string $username;
        private readonly string $password;

        private function __construct(string $path, string $database, string $username, $password) {
            $this->path = $path;
            $this->database = $database;
            $this->username = $username;
            $this->password = $password;
        }

        public static function initialize(string $dataPath) : DBAccessDataProvider {
            $accessDataFile = file_get_contents($dataPath);

            if (!$accessDataFile)
                throw new FileNotFoundException("Could not find the file containing the access information!");

            $neededKeys = ["path", "database", "username", "password"];
            $accessData = json_decode($accessDataFile, true);

            if (count(array_diff(array_keys($accessData), $neededKeys)) > 0)
                throw new InvalidContentException("Missing mandatory information to access the database");

            return new DBAccessDataProvider($accessData["path"], $accessData["database"], $accessData["username"], 
                                            $accessData["password"]);
        }

        public function getPath() : string {
            return $this->path;
        }

        public function getDatabase() : string {
            return $this->database;
        }

        public function getDNS() : string {
            return $this->path . ";dbname=" . $this->database;
        }

        public function getUsername() : string {
            return $this->username;
        }

        public function getPassword() : string {
            return $this->password;
        }
    }
?>
