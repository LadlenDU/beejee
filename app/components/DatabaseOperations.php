<?php

/**
 * Interface DatabaseOperations
 *
 * Интерфейс для работы с БД.
 */
interface DatabaseOperations
{
    public function selectQuery($sql);
    public function query($sql);
    public function escape_string($string);
}