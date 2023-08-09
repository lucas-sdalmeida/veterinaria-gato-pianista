<?php
    namespace lucassdalmeida\gatopianista\veterinaria\persistence\util;

    interface DAO {
        function insert(mixed $entity) : void;

        function findOneByKey(mixed $key) : mixed;

        function findAll() : array;

        function update(mixed $entity) : void;

        function deleteByKey(mixed $key) : void;
    }
?>
