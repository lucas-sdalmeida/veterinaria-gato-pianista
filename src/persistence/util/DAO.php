<?php
    namespace pw2s3\clinicaveterinaria\persistence\util;

    interface DAO {
        function insert(mixed $entity) : mixed;

        function findOneByKey(mixed $key) : mixed;

        function findAll() : array;

        function updade(mixed $entity) : mixed;

        function deleteByKey(mixed $key) : bool;

        function inactivate(mixed $entity) : bool;

        function activateByKey(mixed $key);
    }
?>
