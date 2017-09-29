<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Создатель-партнер.
 *
 * @property string $NAME - Наименование
 * @property string $FTN - ИНН
 * @property double $COMMISSION - Комиссия создателя-юрлица котировки (партнера).
 * @property string $SELL_CHAIN - Цепочка продаж создателя-юрлица котировки (партнера).
 * @property string $CHANNEL_CODE - Код канала продаж.
 * @property string $CHANNEL_NAME - Название канала продаж.
 * @property int $DISCOUNT - Величина скидки за счет КВ.
 * @property string $DEPARTMENT - Наименование Департамента
 * @property string $CURATOR_EMAIL - Email куратора.
 * @property string $CURATOR_LAST_NAME - Фамилия куратора партнера.
 * @property string $CURATOR_FIRST_NAME - Имя куратора партнера.
 * @property string $CURATOR_MIDDLE_NAME - Отчество куратора партнера.
 * @property string $CURATOR_BIRTH_DATE - Дата рождения куратора партнера.
 * @property string $USER_FIO - ФИО пользователя В2В.
 * @property int $NEEDSAPPROVE - Требуется подтверждение куратора (для всех кроме агентов-ИП). 1 - да, 0 - нет
 * @property string $DIVISION - Дивизион
 * @property string $FILIAL - Филиал
 * @property int $DISCOUNT_B2B - Величина скидка за счет B2B.
 */
class Partner extends Container
{
    protected $rules = [
        'NAME' => ['toString'],
        'FTN' => ['toString'],
        'COMMISSION' => ['toDouble'],
        'SELL_CHAIN' => ['toString'],
        'CHANNEL_CODE' => ['toString'],
        'CHANNEL_NAME' => ['toString'],
        'DISCOUNT' => ['toInteger'],
        'DEPARTMENT' => ['toString'],
        'CURATOR_EMAIL' => ['toString', 'email'],
        'CURATOR_LAST_NAME' => ['toString'],
        'CURATOR_FIRST_NAME' => ['toString'],
        'CURATOR_MIDDLE_NAME' => ['toString'],
        'CURATOR_BIRTH_DATE' => ['toString', 'date'],
        'USER_FIO' => ['toString'],
        'NEEDSAPPROVE' => ['toInteger', 'in:0|1'],
        'DIVISION' => ['toString'],
        'FILIAL' => ['toString'],
        'DISCOUNT_B2B' => ['toInteger'],
    ];
}