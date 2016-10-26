<?php

/**
 * Interface IDatabaseOperations
 *
 * Интерфейс для работы с БД.
 */
interface IDatabaseOperations
{
    /**
     * Запрос выбирающий данные.
     *
     * @param string $sql строка запроса SQL
     * @return stdClass объект, в параметре rows будет содержать массив объектов с результатом
     * @throws Exception
     */
    public function selectQuery($sql);

    /**
     * Запрос SQL.
     *
     * @param string $sql строка запроса SQL
     * @return mixed результат или false в случае ошибки
     */
    public function query($sql);

    public function escape_string($string);
}