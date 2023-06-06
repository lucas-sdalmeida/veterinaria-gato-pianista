<?php
    namespace pw2s3\clinicaveterinaria\model\repository\util;

    final class DBAccessDataProvider {
        private readonly string $url;
        private readonly string $username;
        private readonly string $password;

        private function __construct(string $url, string $username, $password) {
            $this->url = $url;
            $this->username = $username;
            $this->password = $password;
        }

        public static function initialize(string $dataPath) : DBAccessDataProvider {
            $accessDataFile = file_get_contents($dataPath);

            if (!$accessDataFile)
                throw new FileNotFoundException(
                    "Could not find the file containing the access information!"
                );

            $neededKeys = ["url", "username", "password"];
            $accessData = json_decode($accessDataFile, true);

            if (count(array_diff(array_keys($accessData), $neededKeys)) > 0)
                throw new InvalidContentException(
                    "Missing mandatory information to access the database"
                );

            return new DBAccessDataProvider($accessData["url"], $accessData["username"], 
                                            $accessData["password"]);
        }

        public function getURL() : string {
            return $this->url;
        }

        public function getUsername() : string {
            return $this->username;
        }

        public function getPassword() : string {
            return $this->password;
        }
    }
?>
