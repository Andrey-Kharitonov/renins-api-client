<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Пролонгация
 *
 * @property string $prolongationNumber - Номер пролонгируемого договора
 * @property string $insurantName - Фамилия страхователя
 * @property string $prolongationDateStart - Дата начала действия пролонгориемого договора (для ЮЛ)
 * @property string $AutomaticProlongation - Автоматическая пролонгация (используется только для ФЛ)
 * @property string $prolongationVIN - VIN номер пролонгируемого ТС
 * @property CoefficientProlongation $CoefficientProlongation - Пролонгация с применением коэффициента пролонгации
 * @property ManualProlongation $ManualProlongation - Расчёт пролонгации вручную (только для ЮЛ)
 */
class Prolongation extends Container
{
    protected $rules = [
        'prolongationNumber' => ['toString', 'required', 'notEmpty'],
        'insurantName' => ['toString'],
        'prolongationDateStart' => ['toString', 'date'],
        'prolongationVIN' => ['toString'],

        'AutomaticProlongation' => ['toLogical'],
        'CoefficientProlongation' => ['container:' . CoefficientProlongation::class],
        'ManualProlongation' => ['container:' . ManualProlongation::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['prolongationNumber', 'insurantName', 'prolongationDateStart', 'prolongationVIN']);
        $this->toXmlTags($xml, ['AutomaticProlongation', 'CoefficientProlongation', 'ManualProlongation']);

        return $this;
    }
}