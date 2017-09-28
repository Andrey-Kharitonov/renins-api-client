<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные по проспекту, потенциальному клиенту, т.е. страхователю.
 *
 * @property string $FirstName - Имя
 * @property string $LastName - Фамилия
 * @property string $MiddleName - Отчество
 * @property string $Phone - Телефон
 * @property string $Email - Адрес электронной почты.
 * @property string $Category - Форматам работы c линк партнером
 *   Lead - Отправка: контактов; ФИО; номера котировки, созданной через DI Link с type=1 и присвоенным к ней значением «Агрегатор»;
 *          информации по расчету в КЦ посредством либо имейл сообщения, либо путем создания очереди активности.
 *   Redirect - Переход с сайта партнера на наш сайт по уникальной ссылке, содержащей номер котировки, на страницу 2 «Котировка».
 *              В этом случае по DI Link создается котировка с type=1 и присвоенным значением «Агрегатор», ее номер вставляется в шаблонную ссылку
 *              с меткой from и осуществляется переход на наш сайт на страницу 2 «Котировка». После перехода на наш сайт в котировку записывается
 *              метка фром из url страницы.
 *   RedirectLead - Аналогичный формату «Редирект» способ работы, только вместе с переходом на наш сайт в колл-центр передаются контакты
 *                  клиента по эл. почте с пометкой «Редирект+лид».
 */
class Prospect extends Container
{
    protected $rules = [
        'FirstName' => ['toString'],
        'LastName' => ['toString'],
        'MiddleName' => ['toString'],
        'Phone' => ['toString'],
        'Email' => ['toString'],
        'Category' => ['toString', 'in:Lead|Redirect|RedirectLead'],
    ];
}