<?php
    namespace pw2s3\clinicaveterinaria\persistence\util;

    use PDO;
    use PDOException;
    use PDOStatement;

    abstract class ConnectionFactory {
        public abstract function getConnection() : PDO;

        public final function prepareStatement(string $sql) : PDOStatement {
            try {
                return $this->getConnection()->prepare($sql);
            }
            catch (PDOException $error) {
                throw new PDOException($error->getMessage());
            }
        }
    }
?>
