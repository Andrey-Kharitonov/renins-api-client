<?php

namespace ReninsApi\Request\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Тип документа и его параметры
 *
 * @property integer $DocumentTypeId - Тип документа
 *   1. Результаты расчета.
 *   3. Дополнительное соглашение.
 *   4. Квитанция – не используется.
 *   5. Другое.
 *   6. Счет.
 *   7. Оригинал полиса
 *   8. Копия полиса.
 *   9. Платежный документ.
 *   10. Дополнительная информация.
 *   11. Список застрахованных (Travel).
 *   12. Дополнительное оборудование.
 *   13. Бланк истории из РСА (ОСАГО).
 *   14. Заявление (Заявление ОСАГО или Заявление на внесение изменений ОСАГО).
 *   15. Акт осмотра.
 *
 *   Доступные типы возвращает ImportPolicy() и getAvailablePolicyDocumentTypes()
 *   Тип 7 - означает фактическое подтверждение полиса (финальный этап)
 *
 * @property string[] $DocumentLabels - Доп. метки по типу.
 *   Например: NewCasco, NoHasStamp, HasStamp
 *   Возможные метки возвращаются вместе с доступными типами печати.
 *   Каждый тип имеет предустановленные метки.
 *   Если указать метку, которая не поддерживается данным типом, сервис вернет ошибку.
 */
class PrintingParams extends Container
{
    protected $rules = [
        'DocumentTypeId' => ['toInteger', 'required', 'notEmpty'],
        'DocumentLabels' => ['array:string'],
    ];
}