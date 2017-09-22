<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Создатель-сотрудник (штатный сотрудник / агент).
 *
 * @property string $LAST_NAME - Фамилия
 * @property string $FIRST_NAME - Имя
 * @property string $MIDDLE_NAME - Отчество
 * @property string $BIRTH_DATE - Дата рождения
 * @property double $COMMISSION - Комиссия создателя-физлица котировки (продавца, агента).
 * @property string $SELL_CHAIN - Цепочка продаж создателя-физлица котировки (продавца, агента).
 * @property string $CHANNEL_CODE - Код канала продаж.
 * @property string $CHANNEL_NAME - Название канала продаж.
 * @property int $DISCOUNT - Величина скидки за счет КВ.
 * @property string $DEPARTMENT - Наименование Департамента.
 * @property string $ROLE - Роль
 * @property string $EMAIL - Email продавца (сотрудника).
 * @property string $MANAGER_CASHBOOK_ID - Идентификатор Cashbook ID менеджера продавца (для агентов).
 * @property string $MANAGER_EMAIL - Email менеджера продавца (для агентов).
 * @property string $DIVISION - Дивизион
 * @property string $FILIAL - Филиал
 * @property int $DISCOUNT_B2B - Величина скидка за счет B2B.
 */
class EMPLOYEE extends Container
{
    protected $rules = [
        'LAST_NAME' => ['toString'],
        'FIRST_NAME' => ['toString'],
        'MIDDLE_NAME' => ['toString'],
        'BIRTH_DATE' => ['toString', 'date'],
        'COMMISSION' => ['toDouble'],
        'SELL_CHAIN' => ['toString'],
        'CHANNEL_CODE' => ['toString'],
        'CHANNEL_NAME' => ['toString'],
        'DISCOUNT' => ['toInteger'],
        'DEPARTMENT' => ['toString'],
        'ROLE' => ['toString', 'in:сотрудник|агент'],
        'EMAIL' => ['toString', 'email'],
        'MANAGER_CASHBOOK_ID' => ['toString'],
        'MANAGER_EMAIL' => ['toString'],
        'DIVISION' => ['toString'],
        'FILIAL' => ['toString'],
        'DISCOUNT_B2B' => ['toInteger'],
    ];
}