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

    /**
     * Верно ли значение направления для выражения сортировки.
     *
     * @param string $order название направления сортировки
     * @return bool
     */
    public function ifValidOrderDirection($order);

    /**
     * Получает максимальную длину колонки в таблице.
     *
     * @param string $table название таблицы
     * @param string $column название колонки
     * @return int
     */
    public function getCharacterMaximumLength($table, $column);
}