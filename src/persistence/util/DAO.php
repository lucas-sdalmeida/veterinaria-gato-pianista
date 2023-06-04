<?php
    namespace pw2s3\clinicaveterinaria\persistence\util;

    interface DAO {
        function insert(mixed $entity) : void;

        function findOneByKey(mixed $key) : mixed;

        function findAll() : array;

        function updade(mixed $entity) : void;

        function deleteByKey(mixed $key) : void;
    }
?>
